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
 * the 'SetPassword' type please see the skeleton
 * class 'SetPasswordType'. Modifications to the skeleton
 * file will prevail any subsequent class generation runs.
 *
 * To define new attributes or adjust existing attributes and their
 * default options modify the schema definition file of
 * the 'SetPassword' type.
 *
 * @see https://github.com/honeybee/trellis
 */

namespace Hlx\Security\User\Model\Aggregate\Embed\Base;

use Honeybee\Model\Aggregate\EmbeddedEntityType;
use Trellis\Common\Options;
use Trellis\Runtime\EntityTypeInterface;
use Trellis\Runtime\Attribute\AttributeInterface;

/**
 * Serves as the base class to the 'SetPassword' type skeleton.
 */
abstract class SetPasswordType extends EmbeddedEntityType
{
    /**
     * Creates a new 'SetPasswordType' instance.
     */
    public function __construct(EntityTypeInterface $parent = null, AttributeInterface $parent_attribute = null)
    {
        parent::__construct(
            'SetPassword',
            [
                new \Trellis\Runtime\Attribute\Text\TextAttribute(
                    'token',
                    $this,
                    array(
                        'mandatory' => true,
                    ),
                    $parent_attribute
                ),
                new \Trellis\Runtime\Attribute\Timestamp\TimestampAttribute(
                    'expires_at',
                    $this,
                    array(
                        'format_native' => 'Y-m-d\\TH:i:s.uP',
                    ),
                    $parent_attribute
                ),
            ],
            new Options(
                []
            ),
            $parent,
            $parent_attribute
        );
    }

    /**
     * Returns the EntityInterface implementor to use when creating new entities.
     *
     * @return string Fully qualified name of an EntityInterface implementation.
     */
    public static function getEntityImplementor()
    {
        return '\\Hlx\\Security\\User\\Model\\Aggregate\\Embed\\SetPassword';
    }
}
