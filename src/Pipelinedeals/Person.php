<?php
// add the namespace
namespace Weblab\Pipelinedeals;

/**
 * The Pipelinesdeals wrapper class wrapping around the person (people) entity from
 * Pipelinesdeals, making it possible to perform rest operations on the person
 * entity
 * 
 * @author Weblab.nl - Thomas Marinissen
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

}
