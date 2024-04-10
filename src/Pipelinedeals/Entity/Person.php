<?php
// add the namespace
namespace Weblab\Pipelinedeals\Entity;

/**
 * The Pipelinesdeals wrapper class wrapping around the person (people) entity from
 * Pipelinesdeals, making it possible to perform rest operations on the person
 * entity
 *
 * @author Weblab.nl - Thomas Marinissen
 *
 * @implements \Weblab\Pipelinedeals\Entity<Person>
 *
 * @property string $first_name;
 * @property string $last_name;
 * @property string $email;
 * @property string $home_city;
 * @property string $home_postal_code;
 * @property string $home_state;
 * @property string $company_id;
 */
class Person extends \Weblab\Pipelinedeals\Entity {

    /**
     * The entity name
     */
    const NAME = 'people';

    /**
     * The name of a single entity
     */
    const ENTITY_NAME = 'person';

    /**
     * The condition fields for the entity
     *
     * @var array
     */
    protected static $conditionFields = [
        'person_name'           => 'string',
        'person_owner'          => 'integer',
        'person_city'           => 'string',
        'person_state'          => 'string',
        'person_zip'            => 'string',
        'person_position'       => 'string',
        'person_company_name'   => 'string',
        'lead_source'           => 'integer',
        'person_status'         => 'integer',
        'person_email'          => 'string',
        'person_full_name'      => 'string',
        'person_phone'          => 'string',
        'person_converted'      => 'datetime range',
        'person_created'        => 'datetime range',
        'person_modified'       => 'datetime range',
        'person_simple_search'  => 'string',
        'person_type'           => 'string',
    ];

}
