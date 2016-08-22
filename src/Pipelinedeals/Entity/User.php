<?php
// add the namespace
namespace Weblab\Pipelinedeals\Entity;

/**
 * The Pipelinesdeals wrapper class wrapping around the user entity from
 * Pipelinesdeals, making it possible to perform rest operations on the user
 * entity
 * 
 * @author Weblab.nl - Thomas Marinissen
 */
class User extends \Weblab\Pipelinedeals\Entity {
    
    /**
     * The entity name
     */
    const NAME = 'users';

    /**
     * The name of a single entity
     */
    const ENTITY_NAME = 'user';

    /**
     * The condition fields for the entity
     *
     * @var array
     */
    protected static $conditionFields = [
        'email'                 => 'string',
        'admin'                 => 'boolean',
        'including_inactive'    => 'boolean',
        'user_level'            => 'integer',
    ];

}
