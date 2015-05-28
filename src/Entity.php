<?php
// add the namespace
namespace Weblab\Pipelinedeals;

/**
 * Abstract base class for a Pipelinedeals entity, adding functionality to perform
 * CRUD operations ont the Pipelinedeals entity.
 * 
 * @author Weblab.nl - Thomas Marinissen
 */
abstract class Entity {
    
    /**
     * The Pipelinedeals API instance
     * 
     * @var \Weblab\Pipelinedeals\Pipelinedeals
     */
    protected $pipelinedealsApi;
    
    /**
     * The entity information
     * 
     * @var \stdClass
     */
    protected $entity;
    
    /**
     * Constructor
     *
     * @param   \Weblab\Pipelinedeals\Pipelinedeals             The pipelinedeals api instance
     * @param   string|null                                     The entity identifier
     */
    public function __construct(\Weblab\Pipelinedeals\Pipelinedeals $pipelinedeals, $id = null) {
        // get access to the pipelinedeals API
        $this->pipelinedealsApi = $pipelinedeals;
        
        // set the entity base
        $entity = new \stdClass();

        // if a entity id was given, get the entity
        if (!is_null($id)) {
            // get the entity from the pipeline deals api
            $entity = $this->pipelinedealsApi->call(static::NAME . '/' . $id . '.json');
        }
        
        // if there is no valid entity, throw an exception
        if (is_null($entity)) {
            throw new \Exception('Not possible to request the ' . static::NAME);
        }
        
        // set the entity
        $this->entity = $entity;
    }
    
    /**
     * Magic getter method
     * 
     * @param   string                              The name of the getter
     * @return  \Weblab\Pipelinedeals\Entity        The instance of this, to make chaining possible
     */
    public function __get($name) {
        // if there is no value in the entity for the given name, return null
        if (!isset($this->entity->$name)) {
            return null;
        }
        
        // return the value for the given name
        return $this->entity->$name;
    }
    
    /**
     * Magic setter method
     * 
     * @param   string                  The name of the variable to set
     * @param   mixed                   The value of the variable to set
     */
    public function __set($name, $value) {
        // set the entity value for the given name
        $this->entity->$name = $value;
    }
    
    /**
     * Set a custom field
     * 
     * @param   int                                                 The custom field identifier of pipelinedeals
     * @param   mixed                                               The custom value to store
     * @return  \Weblab\Pipelinedeals\Entity                        The instance of this, to make chaining possible
     */
    public function setCustomField($id, $value) {
        // if the custom fields object is not set for the entity, add it
        if (!isset($this->entity->custom_fields)) {
            $this->entity->custom_fields = new \stdClass();
        }
        
        // set the custom field label name
        $fieldName = 'custom_label_' . $id;
        
        // add the custom field value
        $this->entity->custom_fields->{$fieldName} = $value;
        
        // done, return the instance of this, to make chaining possible
        return $this;
    }
    
    /**
     * Static method to get the pipelinedeals entity
     *
     * @param   \Weblab\Pipelinedeals\Pipelinedeals             The pipelinedeals api instance
     * @param   int|null                                        The pipelinedeals entity identifier
     * @return  \Weblab\Pipelinedeals\Entity                    The fetched entity from pipelinedeals
     *
     * @throws \Exception
     */
    public static function get(\Weblab\Pipelinedeals\Pipelinedeals $pipelinedeals, $id = null) {
        // get the name of the called class
        $className = get_called_class();
        
        // try getting the entity for the given id from the Pipelinedeals API
        try {
            $entity = new $className($pipelinedeals, $id);
        } catch (\Exception $e) {
            return null;
        }
        
        // done, evertying is all right, return the pipelinedeals entity
        return $entity;
    }
    
    /**
     * Save the entity
     * 
     * @return \stdClass                The result of the save operation on the pipelinedeals api
     *
     * @throws \Exception
     */
    public function save() {
        // format the entity
        $entity = array(static::ENTITY_NAME => $this->entity);
        
        // save the entity
        $result = $this->pipelinedealsApi->call($this->savePath(), $this->saveType(), $entity);
        
        // if the result is null, throw a new exception
        if (is_null($result)) {
            throw new \Exception('Failed to save ' . static::NAME);
        }
        
        // add the result as entity
        $this->entity = $result;
        
        // done, return the result
        return $result;
    }

    /**
     * Get the instance of the pipelinedeals api
     *
     * @return \Weblab\Pipelinedeals\Pipelinedeals              The instance of the Pipelinedeals api
     */
    public function api() {
        return $this->pipelinedealsApi;
    }
    
    /**
     * Remove an entity from the Pipelinedeals API
     * 
     * @return \stdClass
     *
     * @throws \Exception
     */
    public function delete() {
        // only delete the entity, if there is an entity to delete in the
        // Pipelinedeals api
        if (!isset($this->entity->id) || empty($this->entity->id)) {
            throw new \Exception('No ' . static::NAME .  ' to delete');
        }
        
        // remove the entity
        $result = $this->pipelinedealsApi->call(static::NAME . '/' . $this->entity->id . '.json', 'DELETE');
        
        // if the result is null, throw a new exception
        if (is_null($result)) {
            throw new \Exception('Failed to delete ' . static::NAME);
        }
        
        // done, return the result
        return $result;
    }
    
    /**
     * Helper function to get the save path
     * 
     * @return string                   The save path for the operation based on the entity state
     */
    protected function savePath() {
        // return the path if the type is a PUT request
        if ($this->saveType() == 'PUT') {
            return static::NAME . '/' . $this->entity->id . '.json';
        }
        
        // the save request is a post request
        return static::NAME . '.json';
    }
    
    /**
     * Helper function to get the save type
     * 
     * @return string                   The save type (POST or PUT)
     */
    protected function saveType() {
        // if there is an entity id, the return PUT as type
        if (isset($this->entity->id) && !empty($this->entity->id)) {
            return 'PUT';
        }
        
        // the type is a POST request, return POST
        return 'POST';
    }
}
