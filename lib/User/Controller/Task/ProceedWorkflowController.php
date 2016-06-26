<?php

namespace Foh\SystemAccount\User\Controller\Task;

use Foh\SystemAccount\User\Model\Aggregate\UserType;
use Foh\SystemAccount\User\Model\Task\ProceedUserWorkflow\ProceedUserWorkflowCommand;
use Honeybee\Infrastructure\Command\Bus\CommandBusInterface;
use Honeybee\Infrastructure\DataAccess\Finder\FinderMap;
use Honeybee\Model\Command\AggregateRootCommandBuilder;
use Shrink0r\Monatic\Success;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProceedWorkflowController
{
    protected $userType;

    protected $finderMap;

    protected $commandBus;

    protected $urlGenerator;

    public function __construct(
        UserType $userType,
        FinderMap $finderMap,
        CommandBusInterface $commandBus,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->userType = $userType;
        $this->finderMap = $finderMap;
        $this->commandBus = $commandBus;
        $this->urlGenerator = $urlGenerator;
    }

    public function write(Request $request, Application $app)
    {
        if ($request->getMethod() !== 'POST') {
            return 'Method not allowed.';
        }

        $user = $this->fetchUser($request->get('identifier'));

        $result = (new AggregateRootCommandBuilder($this->userType, ProceedUserWorkflowCommand::CLASS))
            ->withProjection($user)
            ->withCurrentStateName($request->get('from'))
            ->withEventName($request->get('via'))
            ->build();

        if (!$result instanceof Success) {
            return $this->templateRenderer->render(
                '@SystemAccount/user/list.twig',
                [ 'errors' => $result->get() ]
            );
        }

        $this->commandBus->post($result->get());

        return $app->redirect($this->urlGenerator->generate('foh.system_account.user.list'));
    }

    protected function fetchUser($identifier)
    {
        $finder = $this->finderMap->getItem($this->userType->getPrefix().'::projection.standard::view_store::finder');
        $results = $finder->getByIdentifier($identifier);
        return $results->getFirstResult();
    }
}
