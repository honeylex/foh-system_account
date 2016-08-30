<?php

namespace Hlx\Security\User\Controller\Task;

use Hlx\Security\Service\AccountService;
use Honeybee\Infrastructure\Template\TemplateRendererInterface;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModifyController
{
    protected $templateRenderer;

    protected $formFactory;

    protected $urlGenerator;

    protected $tokenStorage;

    protected $eventDispatcher;

    protected $userService;

    protected $translator;

    protected $accountService;

    public function __construct(
        TemplateRendererInterface $templateRenderer,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        UserProviderInterface $userService,
        TranslatorInterface $translator,
        AccountService $accountService
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->userService = $userService;
        $this->translator = $translator;
        $this->accountService = $accountService;
    }

    public function read(Request $request)
    {
        $user = $this->userService->loadUserByIdentifier($request->get('identifier'));
        $form = $this->buildForm($user->toArray());

        return $this->templateRenderer->render(
            '@hlx-security/user/task/modify.html.twig',
            [ 'form' => $form->createView(), 'user' => $user ]
        );
    }

    public function write(Request $request, Application $app)
    {
        $user = $this->userService->loadUserByIdentifier($request->get('identifier'));
        $form = $this->buildForm($user->toArray());
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->templateRenderer->render(
                '@hlx-security/user/task/modify.html.twig',
                [ 'form' => $form->createView(), 'user' => $user ]
            );
        }

        $formData = $form->getData();
        $username = $formData['username'];
        $email = $formData['email'];
        $locale = $formData['locale'];

        try {
            if (!$this->userService->userExists($username, $email, [ $user->getIdentifier() ])) {
                $this->accountService->updateUser($user, $formData);
                $token = $this->tokenStorage->getToken();
                if ($token->getUser()->getIdentifier() === $user->getIdentifier() && $user->getLocale() !== $locale) {
                    // Current user locale changed
                    $token = new UsernamePasswordToken(
                        $user->createCopyWith([ 'locale' => $locale ]),
                        null,
                        $token->getProviderKey(),
                        $user->getRoles()
                    );
                    $this->tokenStorage->setToken($token);
                    $event = new InteractiveLoginEvent($request, $token);
                    $this->eventDispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);
                }
                return $app->redirect($this->urlGenerator->generate('hlx.security.user.list'));
            }
        } catch (AuthenticationException $error) {
            $errors = (array) $error->getMessageKey();
        }

        return $this->templateRenderer->render(
            '@hlx-security/user/task/modify.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
                'errors' => isset($errors) ? $errors : [ 'This user is already registered.' ]
            ]
        );
    }

    protected function buildForm(array $data = [])
    {
        $availableRoles = $this->accountService->getAvailableRoles();
        $availableLocales = $this->translator->getFallbackLocales();

        return $this->formFactory->createBuilder(FormType::CLASS, $data, [ 'translation_domain' => 'form' ])
            ->add('username', TextType::CLASS, [ 'constraints' => [ new NotBlank, new Length([ 'min' => 4 ]) ] ])
            ->add('email', EmailType::CLASS, [ 'constraints' => new NotBlank ])
            ->add('firstname', TextType::CLASS, [ 'required' => false ])
            ->add('lastname', TextType::CLASS, [ 'required' => false ])
            ->add('locale', ChoiceType::CLASS, [
                'choices' => array_combine($availableLocales, $availableLocales),
                'constraints' => new Choice($availableLocales),
                'translation_domain' => 'locale'
            ])
            ->add('role', ChoiceType::CLASS, [
                'choices' => array_combine($availableRoles, $availableRoles),
                'constraints' => new Choice($availableRoles),
                'translation_domain' => 'role'
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $fields = $event->getForm()->all();
                $data = array_intersect_key($event->getData(), $fields);
                $event->setData($data);
            })
            ->getForm();
    }
}
