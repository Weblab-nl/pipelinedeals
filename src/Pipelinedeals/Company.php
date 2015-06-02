<?php
// add the namespace
namespace Weblab;

/**
 * The Pipelinesdeals wrapper class wrapping arround the company entity from
 * Pipelinesdeals, making it possible to perform rest operations on the company
 * entity
 *
 * @author Weblab.nl - Thomas Marinissen
 */
class Company extends \Weblab\Entity {

    /**
     * The entity name
     */
    const NAME = 'companies';

    /**
     * The name of a single entity
     */
    const ENTITY_NAME = 'company';

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
