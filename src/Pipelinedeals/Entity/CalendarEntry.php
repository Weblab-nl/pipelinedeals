<?php
// add the namespace
namespace Weblab\Pipelinedeals\Entity;

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

    /**
     * The condition fields for the entity
     *
     * @var array
     */
    protected static $conditionFields = [
        'named'                     => 'string',
        'kind'                      => 'string',
        'owner_id'                  => 'integer',
        'completed'                 => 'boolean',
        'incomplete'                => 'boolean',
        'late'                      => 'boolean',
        'today'                     => 'boolean',
        'tomorrow'                  => 'boolean',
        'this_week'                 => 'boolean',
        'next_week'                 => 'boolean',
        'future'                    => 'boolean',
        'within'                    => 'datetime',
        'starting_at'               => 'string',
        'ending_at'                 => 'string',
        'someday'                   => 'boolean',
        'recurrences_of'            => 'integer',
        'non_recurring'             => 'boolean',
        'base_entries'              => 'boolean',
        'include_inactive'          => 'boolean',
        'calendar_entry_modified'   => 'datetime',
    ];

}
