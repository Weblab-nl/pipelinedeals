<?php
namespace Weblab\Pipelinedeals;

/**
 * Class Query, functionality to build queries to get results for set conditions from pipelinedeals
 *
 * @author  Weblab.nl - Thomas Marinissen
 * @package Weblab\Pipelinedeals
 *
 * @template T of Entity
 */
class Query {

    /**
     * The name of the entity type to query
     *
     * @var
     */
    protected $className;

    /**
     * The pipelinedeals name of the entity to query
     *
     * @var
     */
    protected $pipelinedealsName;

    /**
     * The fields of the entities to get
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The conditions to filter on
     *
     * @var array
     */
    protected $condititions = [];

    /**
     * The offset value
     *
     * @var null
     */
    protected $offset = null;

    /**
     * The number of results, max 200
     *
     * @var null
     */
    protected $limit = null;

    /**
     * Whether the totals should be included in the query or not
     *
     * @var boolean
     */
    protected $totals = false;

    /**
     * Get the connection to pipelinedeals
     *
     * @return \Weblab\Pipelinedeals                The pipelinedeals instance / connection
     *
     * @throws \Exception                           Thrown whenever there is no active pipelinedeals connection set
     */
    public static function connection() {
        return \Weblab\Pipelinedeals::connection();
    }

    /**
     * Query constructor.
     *
     * @param   string                  The class name of the entity to get
     * @param   string                  The pipelinedeals name
     */
    public function __construct($className, $pipelinedealsName) {
        $this->entityName = $className;
        $this->pipelinedealsName = $pipelinedealsName;
    }

    /**
     * Get the class name of the entity to query
     *
     * @return string               The name of the entity
     */
    public function className() {
        return $this->entityName;
    }

    /**
     * Get the name of the entities to query used by pipelinedeals
     *
     * @return string               The pipelinedeals name
     */
    public function pipelinedealsName() {
        return $this->pipelinedealsName;
    }

    /**
     * Collect results from pipelinedeals for the query
     *
     * @param   array                                           The fields to return for every found entity
     * @return  \Weblab\Pipelinedeals\Collection<T>                The results
     */
    public function get(array $attributes = []) {
        // set the attributes
        $this->attributes = $attributes;

        // create a new collection and return the result
        return new \Weblab\Pipelinedeals\Collection(clone $this);
    }

    /**
     * Execute a query on pipelinedeals
     *
     * @return \stdClass|null                The results from pipelinedeals for the query
     */
    public function getFromPipelinedeals() {
        // get access tot the pipelinedeals connection
        $connection = self::connection();

        // generate the query path
        $queryPath = $this->queryPath();

        // generate the path to call
        $path = $this->pipelinedealsName . '.json?' . implode('&', $queryPath);

        // call to pipelinedeals and get the result
        return $connection->call($path);
    }

    /**
     * Set the fields to return for the entities
     *
     * @param   array                                   The fields to return for every found entity
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function select(array $attributes) {
        // set the attributes
        $this->attributes = $attributes;

        // done, return the instance of this to make chaining possible
        return $this;
    }

    /**
     * Add a condition to filter the pipelinedeals entities for
     *
     * @param   string                                  The attribute the conditition is for
     * @param   mixed                                   The lower bound condition value
     * @param   mixed|null                              The uper bound condition value
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function where($attribute, $value, $value2 = null) {
        // get the class name of the entity
        $className = $this->className();

        // get the conditions for the entity
        $allowedConditions = $className::conditionFields();

        // id the given attribute is a date time field, add it as date time range condition, otherwise add it as
        // a normal condition
        if (isset($allowedConditions[$attribute]) && ($allowedConditions[$attribute] == 'datetime' || $allowedConditions[$attribute] == 'datetime range')) {
            return $this->whereDateTime($attribute, $value, $value2);
        }

        // done, set the condititon and return the instance of this, to make chaining possible
        return $this->whereRaw('conditions[' . $attribute . ']=' . $value);
    }

    /**
     * Alias of the where method
     *
     * @param   string                                  The attribute the conditition is for
     * @param   mixed                                   The lower bound condition value
     * @param   mixed|null                              The uper bound condition value
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function condition($attribute, $value, $value2 = null) {
        return $this->where($attribute, $value, $value2);
    }

    /**
     * Add a date time range condition
     *
     * @param   string                                  The attribute the conditition is for
     * @param   mixed                                   The lower bound condition value
     * @param   mixed|null                              The uper bound condition value
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function whereDateTime($attribute, $value, $value2 = null) {
        // create the base of the attribute condition
        $attribute = 'conditions[' . $attribute . ']';

        // create the base condition
        $condition = $attribute . '[from_date]=' . $value;

        // if there is a second value set, add the upper bound condition
        if (!is_null($value2)) {
            $condition .= '&' . $attribute . '[to_date]=' . $value2;
        }

        // add the condition
        return $this->whereRaw($condition);
    }

    /**
     * Add a raw query condition to filter the results by
     *
     * @param   string                                  Raw condititon to filter the results by
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function whereRaw($condition) {
        $this->condititions[] = $condition;

        // done, return the instance of this to make chaining possible
        return $this;
    }

    /**
     * Alias of whereRaw
     *
     * @param   string                                  Raw condititon to filter the results by
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function conditionRaw($condition) {
        return $this->whereRaw($condition);
    }

    /**
     * The number of results to get. A maximum of 200 results can be returned in 1 call. The default value is 5
     *
     * @param   int                                     The number of results to get
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function take(int $value = 200) {
        // make sure the limit has a maximum value of 200;
        if ($value > 200) {
            $value = 200;
        }

        // set the limit
        $this->limit = $value;

        // done, return the instance of this to make chaining possible
        return $this;
    }

    /**
     * Alias of take
     *
     * @param   int                                     The number of results to get
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function limit(int $value = 200) {
        return $this->take($value);
    }

    /**
     * The page to get from the result set
     *
     * @param   int                                     The page to get
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function page(int $value) {
        // set the offset value
        $this->offset = $value;

        // done, return the instance of this to make chaining possible
        return $this;
    }

    /**
     * Set whether the totals should be included in the query
     *
     * @param   boolean                                 Whether the totals should be included in the query or not
     * @return  \Weblab\Pipelinedeals\Query<T>             The instance of this, to make chaining possible
     */
    public function totals(bool $totals = false) {
        $this->totals = $totals;

        // done, return the instance of this to make chaining possible
        return $this;
    }

    /**
     * Create the query path
     *
     * @return array                The query path as array
     */
    public function queryPath() {
        // variable for holding the path
        $path = [];

        // add the conditions to the path
        foreach ($this->condititions as $conditition) {
            $path[] = $conditition;
        }


        // add the limit to the path if set
        if (!is_null($this->limit)) {
            $path[] = 'per_page=' . $this->limit;
        }

        // add the offset to the path if set
        if (!is_null($this->offset)) {
            $path[] = 'page=' . $this->offset;
        }

        // add the totals are set to be true, add it to the path
        if ($this->totals) {
            $path[] = 'totals=' . ($this->totals ? 'true' : 'false') . '';
        }

        // if specific attributes are requested, add the attributes to the path
        if (count($this->attributes)) {
            $path[] = 'attrs=' . implode(',', $this->attributes);
        }

        // done, return the path
        return $path;
    }
}
