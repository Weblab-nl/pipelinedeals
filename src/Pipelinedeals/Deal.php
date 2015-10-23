<?php
// add the namespace
namespace Weblab\Pipelinedeals;

/**
 * The Pipelinesdeals wrapper class wrapping around the deal entity from
 * Pipelinesdeals, making it possible to perform rest operations on the deal
 * entity
 * 
 * @author Weblab.nl - Thomas Marinissen
 */
class Deal extends \Weblab\Pipelinedeals\Entity {
    
    /**
     * The entity name
     */
    const NAME = 'deals';
    
    /**
     * The name of a single entity
     */
    const ENTITY_NAME = 'deal';

}
