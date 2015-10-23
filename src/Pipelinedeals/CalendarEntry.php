<?php
// add the namespace
namespace Weblab\Pipelinedeals;

/**
 * The Pipelinesdeals wrapper class wrapping around a calendar entry from
 * Pipelinesdeals, making it possible to perform rest operations on the calendar
 * entry entity
 * 
 * @author Weblab.nl - Thomas Marinissen
 */
class CalendarEntry extends \Weblab\Pipelinedeals\Entity {
    
    /**
     * The entity name
     */
    const NAME = 'calendar_entries';

    /**
     * The name of a single entity
     */
    const ENTITY_NAME = 'calendar_entry';

}
