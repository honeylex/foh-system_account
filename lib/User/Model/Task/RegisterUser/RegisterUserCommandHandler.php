<?php

namespace Hlx\Security\User\Model\Task\RegisterUser;

use Hlx\Security\User\Model\Aggregate\UserType;
use Honeybee\Infrastructure\Event\Bus\EventBusInterface;
use Honeybee\Infrastructure\DataAccess\DataAccessServiceInterface;
use Honeybee\Model\Task\CreateAggregateRoot\CreateAggregateRootCommandHandler;
use Psr\Log\LoggerInterface;

class RegisterUserCommandHandler extends CreateAggregateRootCommandHandler
{
    public function __construct(
        UserType $user_type,
        DataAccessServiceInterface $data_access_service,
        EventBusInterface $event_bus,
        LoggerInterface $logger
    ) {
        parent::__construct($user_type, $data_access_service, $event_bus, $logger);
    }
}