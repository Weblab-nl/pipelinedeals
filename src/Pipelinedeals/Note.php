<?php
// add the namespace
namespace Weblab\Pipelinedeals;

/**
 * The Pipelinesdeals wrapper class wrapping around the note entity from
 * Pipelinesdeals, making it possible to perform rest operations on the note
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
