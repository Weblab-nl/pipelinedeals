<?php
namespace Weblab\Pipelinedeals;

/**
 * Collection class, to handle collections of pipelinedeals entities
 *
 * @author Weblab.nl - Thomas Marinissen
 */
class Collection implements \Iterator, \Countable, \ArrayAccess {

    /**
     * The query instance the result set is based on
     *
     * @var \Weblab\Pipelinedeals\Query|null
     */
    protected $query = null;

    /**
     * The results
     *
     * @var \Weblab\Pipelinedeals\Entity[]
     */
    protected $results = [];

    /**
     * The pagination information
     *
     * @var \stdClass|null
     */
    protected $pagination = null;

    /**
     * The current key
     *
     * @var int
     */
    protected $currentKey = 0;

    /**
     * The result set
     *
     * @param \Weblab\Pipelinedeals\Query             The query to get the results from
     */
    public function __construct(\Weblab\Pipelinedeals\Query $query = null) {
        // set the query
        $this->query = $query;

        // if there is an empty query given, done, return
        if (is_null($query)) {
            return;
        }

        // get the result from pipelinedeals
        $result = $query->getFromPipelinedeals();

        // if there is no result, return
        if (is_null($result)) {
            return;
        }

        // if there are entries, add every entry
        if (isset($result->entries)) {
            foreach ($result->entries as $entry) {
                // get the entity name
                $className = $query->className();

                // create a new entity class
                $this->results[] = new $className($entry);
            }
        }

        // if the pagination is set, add the pagination parameters
        if (isset($result->pagination)) {
            $this->pagination = $result->pagination;
        }
    }

    /**
     * Return the current result
     *
     * @return \stdClass
     */
    public function current() {
        return $this->results[$this->currentKey];
    }

    /**
     * Get the current key
     *
     * @return int
     */
    public function key() {
        return $this->currentKey;
    }

    /**
     * Get the next result
     *
     * @return int
     */
    public function next() {
        return $this->currentKey += 1;
    }

    /**
     * Set the current key back to 0
     */
    public function rewind() {
        $this->currentKey = 0;
    }

    /**
     * Return whether a value exists (key in results)
     *
     * @return
     */
    public function valid() {
        return isset($this->results[$this->currentKey]);
    }

    /**
     * Return the number of results in this result set
     *
     * @return int
     */
    public function count() {
        return count($this->results);
    }

    /**
     * Return whether an offset exists
     *
     * @param   mixed               The offset
     * @return  boolean             Whether the offset is part of the object or not
     */
    public function offsetExists($offset) {
        return array_key_exists($offset, $this->results);
    }

    /**
     * Return the value for the offset
     *
     * @param   mixed                   The offset
     * @return  mixed                   The value for the offset
     */
    public function offsetGet($offset) {
        return $this->results[$offset]->_source;
    }

    /**
     * Set a value for an offset
     *
     * @param   mixed               The offset
     * @param   mixed               The value to set
     */
    public function offsetSet($offset, $value) {
        $this->results[$offset] = $value;
    }

    /**
     * Unset a value for the offset
     *
     * @param   mixed               The offset to unset the value for
     */
    public function offsetUnset($offset) {
        unset($this->results[$offset]);
    }

    /**
     * Get the total number of results
     *
     * @return int              The total number of results
     */
    public function countTotalResults() {
        // if the aggregated information is not available, return the total number of results currently in the result set
        if (is_null($this->pagination) || !isset($this->pagination->total)) {
            return count($this->results);
        }

        // done, return the total number of results
        return $this->pagination->total;
    }

    /**
     * Get the current page number
     *
     * @return int              The current page
     */
    public function currentPageNumber() {
        // if there is no page number available, return the default page number
        if (is_null($this->pagination) || !isset($this->pagination->page)) {
            return 1;
        }

        // return the page number
        return $this->pagination->page;
    }

    /**
     * Get the maximum page number
     *
     * @return int              The maximum page number
     */
    public function maximumPageNumber() {
        // if there is no maximum page number available, return the default maximum page number
        if (is_null($this->pagination) || !isset($this->pagination->page)) {
            return 1;
        }

        // return the page number
        return $this->pagination->pages;
    }

    /**
     * Get the result set of the next page
     *
     * @return \Weblab\Pipelinedeals\Collection                 The collection of entities for the next page
     */
    public function nextPage() {
        // get the current page number
        $currentPageNumber = $this->currentPageNumber();

        // get the next page results and return them
        return $this->query()
            ->page($currentPageNumber + 1)
            ->get();
    }

    /**
     * Get the result set of the previous page
     *
     * @return \Weblab\Pipelinedeals\Collection                 The collection of entities for the previous page
     */
    public function previousPage() {
        // get the current page number
        $currentPageNumber = $this->currentPageNumber();

        // get the next page results and return them
        return $this->query()
            ->page($currentPageNumber - 1)
            ->get();
    }

    /**
     * Get the query object if set
     *
     * @return null|\Weblab\Pipelinedeals\Query
     */
    public function query() {
        return $this->query;
    }

}