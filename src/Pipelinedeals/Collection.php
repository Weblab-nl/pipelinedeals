<?php
namespace Weblab\Pipelinedeals;

/**
 * Collection class, to handle collections of pipelinedeals entities
 *
 * @author Weblab.nl - Thomas Marinissen
 */
class Collection implements Iterator, Countable, ArrayAccess {

    /**
     * The results
     *
     * @var \stdClass[]
     */
    protected $results = [];

    /**
     * The current key
     *
     * @var int
     */
    protected $currentKey = 0;

    /**
     * The result set
     *
     * @param array             Array containing the result set as returned from the elasticsearch search request
     */
    public function __construct($result) {
        // if there are no result, make the result set empty
        if (is_null($result)) {
            return;
        }

        $this->results = $result;
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

}