<?php
// add the namespace
namespace Weblab\Pipelinedeals\Entity;

/**
 * The Pipelinesdeals wrapper class wrapping around the company entity from
 * Pipelinesdeals, making it possible to perform rest operations on the company
 * entity
 *
 * @author Weblab.nl - Thomas Marinissen
 */
class Company extends \Weblab\Pipelinedeals\Entity {

    /**
     * The entity name
     */
    const NAME = 'companies';

    /**
     * The name of a single entity
     */
    const ENTITY_NAME = 'company';

    /**
     * The condition fields for the entity
     *
     * @var array
     */
    protected static $conditionFields = [
        'company_id'            => 'integer',
        'company_name'          => 'string',
        'company_description'   => 'string',
        'company_email'         => 'string',
        'company_phone'         => 'string',
        'company_fax'           => 'string',
        'company_address'       => 'string',
        'company_address2'      => 'string',
        'company_city'          => 'string',
        'company_state'         => 'string',
        'company_zip'           => 'string',
        'company_country'       => 'string',
        'company_created'       => 'datetime range',
        'company_modified'      => 'datetime range',
        'company_imported_at'   => 'datetime range',
    ];

    /**
     * Save the company in pipelinedeals
     *
     * @return \stdClass                The result of the save operation on the pipelinedeals api
     *
     * @throws \Exception
     */
    public function save() {
        // only save the company if there is a name
        if (!isset($this->entity->name) || empty($this->entity->name)) {
            throw new \Exception('No ' . static::NAME . ' name given');
        }

        // call and return the result from the parent
        return parent::save();
    }

}
