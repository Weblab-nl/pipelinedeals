<?php
// add the namespace
namespace Weblab\Pipelinedeals\Entity;

/**
 * The Pipelinesdeals wrapper class wrapping around the deal entity from
 * Pipelinesdeals, making it possible to perform rest operations on the deal
 * entity
 *
 * @author Weblab.nl - Thomas Marinissen
 *
 * @implements \Weblab\Pipelinedeals\Entity<Deal>
 * @property int|float $value
 * @property object $custom_fields
 * @property int $deal_stage_id
 * @property int $primary_contact_id
 * @property int $company_id
 * @property int $source_id
 * @property string $name
 * @property string $probability
 * @property string $status
 * @property string $summary
 * @property string $expected_close_date
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

    /**
     * The condition fields for the entity
     *
     * @var array
     */
    protected static $conditionFields = [
        'deal_id'               => 'integer',
        'deal_name'             => 'string',
        'deal_percentage'       => 'integer',
        'deal_source'           => 'integer',
        'deal_owner'            => 'integer',
        'deal_value'            => 'integer',
        'deal_stage'            => 'integer',
        'exp_close'             => 'datetime range',
        'deal_created'          => 'datetime range',
        'deal_modified'         => 'datetime range',
        'deal_closed_time'      => 'datetime range',
        'person_full_name'      => 'integer',
        'person_email'          => 'integer',
        'person_phone'          => 'integer',
        'person_city'           => 'integer',
        'person_state'          => 'integer',
        'person_work_zip'       => 'integer',
        'person_zip'            => 'integer',
        'person_company_name'   => 'integer',
    ];

    /**
     * Create a new query object
     *
     * @return \Weblab\Pipelinedeals\Query              The query builder object
     */
    public function newQuery() {
        // create the query builder object
        $query = new \Weblab\Pipelinedeals\Query(static::class, static::NAME);

        // done, return the query object, including the totals
        return $query->totals(true);
    }

}
