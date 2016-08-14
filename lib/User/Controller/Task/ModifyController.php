<?php

namespace Hlx\Security\User\Controller\Task;

use Hlx\Security\Service\AccountService;
use Honeybee\Infrastructure\Template\TemplateRendererInterface;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ModifyController
{
    protected $templateRenderer;

    protected $formFactory;

    protected $urlGenerator;

    protected $userService;

    protected $accountService;

    public function __construct(
        TemplateRendererInterface $templateRenderer,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        UserProviderInterface $userService,
        AccountService $accountService
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->userService = $userService;
        $this->accountService = $accountService;
    }

    public function read(Request $request)
    {
        $user = $this->userService->loadUserByIdentifier($request->get('identifier'));
        $form = $this->buildUserForm($user->toArray());

        return $this->templateRenderer->render(
            '@hlx-security/user/task/modify.html.twig',
            [ 'form' => $form->createView(), 'user' => $user ]
        );
    }

    public function write(Request $request, Application $app)
    {
        $user = $this->userService->loadUserByIdentifier($request->get('identifier'));

        $form = $this->buildUserForm($user->toArray());
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

        try {
            if (!$this->userService->userExists($username, $email, [ $user->getIdentifier() ])) {
                $this->accountService->updateUser($user, $formData);
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

    protected function buildUserForm(array $data = [])
    {
        return $this->formFactory->createBuilder(FormType::CLASS, $data)
            ->add('username', TextType::CLASS, [ 'constraints' => [ new NotBlank, new Length([ 'min' => 4 ]) ] ])
            ->add('email', EmailType::CLASS, [ 'constraints' => new NotBlank ])
            ->add('firstname', TextType::CLASS, [ 'required' => false ])
            ->add('lastname', TextType::CLASS, [ 'required' => false ])
            ->add('locale', ChoiceType::CLASS, [
                'choices' => [ 'English' => 'en_GB', 'Deutsch' => 'de_DE' ],
                'constraints' => new Choice([ 'en_GB', 'de_DE' ])
            ])
            ->add('role', ChoiceType::CLASS, [
                'choices' => [ 'Administrator' => 'administrator', 'User' => 'user' ],
                'constraints' => new Choice([ 'administrator', 'user' ]),
            ])
            ->getForm();
    }
}
