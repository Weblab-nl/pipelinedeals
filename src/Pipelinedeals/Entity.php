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
     * The entity information
     * 
     * @var \stdClass
     */
    protected $entity;

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
     * Find a pipelinedeals entity
     *
     * @param   int                                             The pipelinedeal identifier of the
     * @return  null|\Weblab\Pipelinedeals\Entity               The entity from pipelinedeals
     */
    public static function find($id) {
        // get the data from pipelinedeals for the given identifier
        $entityData = self::connection()->call(static::NAME . '/' . $id . '.json');
        
        // if there is no proper entity data, return null
        if (is_null($entityData)) {
            return null;
        }
        
        // get the name of the called class
        $className = get_called_class();
        
        // create the new entity object
        return new $className($entityData);
    }

    /**
     * Find a pipelinedeals entity or fail
     *
     * @param   int                                         The pipelinedeal identifier of the pipelinedeals entity
     * @return  \Weblab\Pipelinedeals\Entity                The entity from pipelinedeals
     *
     * @throws  \Exception                                  Thrown if the entity can not be found
     */
    public static function findOrFail($id) {
        // get the entity
        $object = self::find($id);

        // if there is an entity, return it
        if (!is_null($object)) {
            return $object;
        }

        // throw an error
        throw new \Exception('Not possible to request the ' . static::NAME);
    }

    /**
     * Find a pipelinedeals entity by pipelinedeals identifier, if not found create a new entity
     *
     * @param   int                                         The pipelinedeal identifier of the pipelinedeals entity
     * @return  \Weblab\Pipelinedeals\Entity                The entity from pipelinedeals
     */
    public static function findOrNew($id) {
        // find the object
        $object = self::find($id);

        // if there is no object, create a new object
        if (is_null($object)) {
            // get the name of the called class
            $className = get_called_class();

            // create a new entity object
            $object = new $className();
        }

        // done, return the entity object
        return $object;
    }
    
    /**
     * Constructor
     *
     * @param  \stdClass|null                       The entity data
     */
    public function __construct(\stdClass $entity = null) {
        // make sure that the entity is always a stdClass object
        if (is_null($entity)) {
            $entity = new \stdClass();
        }
        
        // set the entity
        $this->entity = $entity;
    }
    
    /**
     * Magic getter method
     * 
     * @param   string                  The name of the getter
     * @return  mixed                   The value found, null for no value
     */
    public function __get($name) {
        // if there is no value in the entity for the given name, return null
        if (!isset($this->entity->{$name})) {
            return null;
        }
        
        // return the value for the given name
        return $this->entity->{$name};
    }
    
    /**
     * Magic setter method
     * 
     * @param   string                              The name of the variable to set
     * @param   mixed                               The value of the variable to set
     * @return  \Weblab\Pipelinedeals\Entity        The instance of this, to make chaining possible
     */
    public function __set($name, $value) {
        // set the entity value for the given name
        $this->entity->{$name} = $value;

        // done, return the instance of this, to make chaining possible
        return $this;
    }

    /**
     * Set the entity based on the standard class
     *
     * @param   \stdClass                           The entity information to set
     * @return  \Weblab\Pipelinedeals\Entity        The instance of this, to make chaining possible
     */
    public function fill(\stdClass $entity) {
        // set the entity
        $this->entity = $entity;

        // return the instance of this, to make chaining possible
        return $this;
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
     * @param   int|null                                        The pipelinedeals entity identifier
     * @return  \Weblab\Pipelinedeals\Entity                    The fetched entity from pipelinedeals
     */
    public static function get($id = null) {
        return self::findOrNew($id);
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
        $entity = [static::ENTITY_NAME => $this->entity];
        
        // save the entity
        $result = self::connection()->call($this->savePath(), $this->saveType(), $entity);
        
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
        $result = self::connection()->call(static::NAME . '/' . $this->entity->id . '.json', 'DELETE');
        
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
