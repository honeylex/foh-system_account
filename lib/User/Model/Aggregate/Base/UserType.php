<?php
/*
 * AUTOGENERATED CODE - DO NOT EDIT!
 *
 * This base class was generated by the Trellis library and
 * must not be modified manually. Any modifications to this
 * file will be lost upon triggering the next automatic
 * class generation.
 *
 * If you are looking for a place to alter the behaviour of
 * the 'User' type please see the skeleton
 * class 'UserType'. Modifications to the skeleton
 * file will prevail any subsequent class generation runs.
 *
 * To define new attributes or adjust existing attributes and their
 * default options modify the schema definition file of
 * the 'User' type.
 *
 * @see https://github.com/honeybee/trellis
 */

namespace Hlx\Security\User\Model\Aggregate\Base;

use Trellis\Common\Options;
use Workflux\StateMachine\StateMachineInterface;
use Hlx\Security\User\Model\Aggregate\Base;
use Honeybee\Model\Aggregate\AggregateRootType;

/**
 * Serves as the base class to the 'User' type skeleton.
 */
abstract class UserType extends AggregateRootType
{
    /**
     * StateMachineInterface $workflow_state_machine
     */
    protected $workflow_state_machine;

    /**
     * Creates a new 'UserType' instance.
     *
     * @param StateMachineInterface $workflow_state_machine
     */
    public function __construct(StateMachineInterface $workflow_state_machine)
    {
        $this->workflow_state_machine = $workflow_state_machine;

        parent::__construct(
            'User',
            [
                new \Trellis\Runtime\Attribute\Text\TextAttribute(
                    'username',
                    $this,
                    array(
                        'mandatory' => true,
                        'min_length' => 1,
                        'max_length' => 50,
                    )
                ),
                new \Trellis\Runtime\Attribute\Email\EmailAttribute(
                    'email',
                    $this,
                    array(
                        'mandatory' => true,
                    )
                ),
                new \Trellis\Runtime\Attribute\Choice\ChoiceAttribute(
                    'role',
                    $this,
                    array(
                        'mandatory' => true,
                        'min_length' => 1,
                        'max_length' => 255,
                        'allowed_values' => array(
                            'administrator' => 'administrator',
                            'user' => 'user',
                        ),
                    )
                ),
                new \Trellis\Runtime\Attribute\Text\TextAttribute(
                    'firstname',
                    $this,
                    array(
                        'max_length' => 100,
                    )
                ),
                new \Trellis\Runtime\Attribute\Text\TextAttribute(
                    'lastname',
                    $this,
                    array(
                        'max_length' => 100,
                    )
                ),
                new \Trellis\Runtime\Attribute\Text\TextAttribute(
                    'password_hash',
                    $this,
                    array(
                        'min_length' => 50,
                        'max_length' => 100,
                    )
                ),
                new \Trellis\Runtime\Attribute\ImageList\ImageListAttribute(
                    'images',
                    $this,
                    array(
                        'max_count' => 5,
                    )
                ),
                new \Trellis\Runtime\Attribute\EmbeddedEntityList\EmbeddedEntityListAttribute(
                    'tokens',
                    $this,
                    array(
                        'entity_types' => array(
                            '\\Hlx\\Security\\User\\Model\\Aggregate\\Embed\\VerificationType',
                        ),
                    )
                ),
            ],
            new Options(
                array(
                'vendor' => 'Hlx',
                'package' => 'Security',
                'is_hierarchical' => false,
            )
            )
        );
    }

    /**
     * Returns an (immutable) state-machine instance responseable for controlling an entity's workflow.
     *
     * @return StateMachineInterface
     */
    public function getWorkflowStateMachine()
    {
        return $this->workflow_state_machine;
    }

    /**
     * Returns the EntityInterface implementor to use when creating new entities.
     *
     * @return string Fully qualified name of an EntityInterface implementation.
     */
    public static function getEntityImplementor()
    {
        return '\\Hlx\\Security\\User\\Model\\Aggregate\\User';
    }
}
