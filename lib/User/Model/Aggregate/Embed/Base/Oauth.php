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
 * 'Oauth' entities please see the skeleton
 * class 'Oauth'. Modifications to the skeleton
 * file will prevail any subsequent class generation runs.
 *
 * To define new attributes or adjust existing attributes and their
 * default options modify the schema definition file of
 * the 'Oauth' type.
 *
 * @see https://github.com/honeybee/trellis
 */

namespace Hlx\Security\User\Model\Aggregate\Embed\Base;

use Honeybee\Model\Aggregate\EmbeddedEntity;

/**
 * Serves as the base class to the 'Oauth' entity skeleton.
 *
 * This class contains definitions for attributes and their options available
 * on 'Oauth' instances. Modifications to getters and setters
 * as well as new additional helper methods should not be placed in here,
 * but be implemented within the skeleton class 'Oauth'.
 *
 * Attributes can have default and null values set via their options. The keys
 * are named 'default_value' and 'null_value' respectively.
 *
 * This class extends the 'EmbeddedEntity' class to get the change events and
 * validation handling behaviour of that class.
 */
abstract class Oauth extends EmbeddedEntity
{
    /**
     * Returns the current value of the 'id' attribute on this
     * 'Oauth' entity. The 'default_value' option set for
     * this attribute is returned if no value was set. If neither a value nor
     * default value was set the 'null_value' option value is returned.
     *
     * @return mixed Value or default value of attribute 'id'. Null value otherwise (defaults to NULL).
     */
    public function getId()
    {
        return $this->getValue('id');
    }

    /**
     * Returns the current value of the 'service' attribute on this
     * 'Oauth' entity. The 'default_value' option set for
     * this attribute is returned if no value was set. If neither a value nor
     * default value was set the 'null_value' option value is returned.
     *
     * @return mixed Value or default value of attribute 'service'. Null value otherwise (defaults to NULL).
     */
    public function getService()
    {
        return $this->getValue('service');
    }

    /**
     * Returns the current value of the 'token' attribute on this
     * 'Oauth' entity. The 'default_value' option set for
     * this attribute is returned if no value was set. If neither a value nor
     * default value was set the 'null_value' option value is returned.
     *
     * @return mixed Value or default value of attribute 'token'. Null value otherwise (defaults to NULL).
     */
    public function getToken()
    {
        return $this->getValue('token');
    }

    /**
     * Returns the current value of the 'expires_at' attribute on this
     * 'Oauth' entity. The 'default_value' option set for
     * this attribute is returned if no value was set. If neither a value nor
     * default value was set the 'null_value' option value is returned.
     *
     * @return mixed Value or default value of attribute 'expires_at'. Null value otherwise (defaults to NULL).
     */
    public function getExpiresAt()
    {
        return $this->getValue('expires_at');
    }
}
