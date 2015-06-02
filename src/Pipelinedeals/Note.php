<?php
// add the namespace
namespace Weblab\Pipelinedeals;

/**
 * The Pipelinesdeals wrapper class wrapping arround the person (people) entity from
 * Pipelinesdeals, making it possible to perform rest operations on the person
 * entity
 * 
 * @author Weblab.nl - Thomas Marinissen
 */
class Note extends \Weblab\Pipelinedeals\Entity {
    
    /**
     * The entity name
     */
    const NAME = 'notes';
    
    /**
     * The name of a single entity
     */
    const ENTITY_NAME = 'note';

}
