<?php

abstract class CG_ActiveRecord
{
    // Contains all the fields in the database table and other properties.
    public static $attributes = array();
    protected $_table_name;
    protected $_errors = array();
    protected $_required_attributes = array(
        'name', 'user_id', 'project_id'
    );
    
    /*
     * Retrieve a single database record through ID attribute
     * and assign the values into the object properties
     * 
     * @param ID
     * 
     * @return boolean
     */
    public function FindByID($id=0)
    {
        global $wpdb;
        
        if(!empty($id))
        {
            $query = "SELECT * FROM ".$this->_table_name." WHERE id = %d LIMIT 1";
            $query = $wpdb->prepare($query, $id);

            $record = $wpdb->get_row($query, OBJECT);
            
            if(!empty($record->id))
            {
                foreach($this::$attributes as $attribute)
                {
                    if(is_array($attribute))
                    {
                        foreach($attribute as $attr)
                        {
                            $this->$attr = $record->$attr;
                        }
                        continue;
                    }
                    $this->$attribute = $record->$attribute;
                }
                return $this;
            }
        }
        return FALSE;
    }
    
    /*
     * Retrieve a single database record through project ID and type attribute
     * 
     * @param project ID
     * @param type
     * 
     * @return mixed (class object if record found, otherwise false)
     */
    public function FindByProjectID($project_id=0, $type=0)
    {
        global $wpdb;
        
        if(!empty($project_id))
        {
            if(!empty($type))
            {
                $query = "SELECT * FROM ".$this->_table_name." WHERE project_id = '%s' AND type = '%s' LIMIT 1";
                $query = $wpdb->prepare($query, $project_id, $type);
            }
            else
            {
                $query = "SELECT * FROM ".$this->_table_name." WHERE project_id = '%s' LIMIT 1";
                $query = $wpdb->prepare($query, $project_id);
            }
            
            $record = $wpdb->get_row($query, OBJECT);
            
            if(!empty($record->id))
            {
                foreach($this::$attributes as $attribute)
                {
                    if(is_array($attribute))
                    {
                        foreach($attribute as $attr)
                        {
                            $this->$attr = $record->$attr;
                        }
                        continue;
                    }
                    $this->$attribute = $record->$attribute;
                }
                return $this;
            }
        }
        return FALSE;
    }
    
    /*
     * Create or Update record into the database
     * 
     * @return (mixed) TRUE if success, array of string errors if fails
     */
    public function Save()
    {
        return !empty($this->id) ? $this->Update() : $this->Create();
    }
    
    /*
     * Add new table record in the database
     */
    public function Create()
    {
        global $wpdb;
        
        if($this->_ValidRequiredAttributes())
        {
            $create = $wpdb->insert($this->_table_name, $this->_AttributesArrayWithValue());
            
            if($create)
            {
                $this->id = $wpdb->insert_id;
                
                if(!empty($this->id))
                    return TRUE;
            }
            $this->_errors[] = 'Failed to add new record data.';
        }
        
        return $this->_errors;
    }
    
    /*
     * Edit a table record in the database
     */
    public function Update()
    {
        global $wpdb;
        
        if($this->_ValidRequiredAttributes())
        {
            $update = $wpdb->update($this->_table_name, $this->_AttributesArrayWithValue(), array('id'=>$this->id));
            
            if($update >= 0)
                return TRUE;
            
            $this->_errors[] = 'Failed to edit data record.';
        }
        
        return $this->_errors;
    }
    
    /*
     * Remove a table record in the database
     * 
     * @param $where_conditions
     * 
     * @return mixed (Return true if success and array of errors if fails)
     */
    public function Delete($where_conditions=array())
    {
        global $wpdb;
        
        $where_conditions['id'] = $this->id;
        
        if(!empty($this->id))
        {
            $delete = $wpdb->delete($this->_table_name, $where_conditions);
            
            if($delete >= 0)
                return TRUE;
            
            $this->_errors[] = 'Failed to delete data record.';
        }
        
        return $this->_errors;
    }
    
    /*
     * Check for an empty required attributes.
     * 
     * @return boolean
     */
    protected function _ValidRequiredAttributes()
    {
        foreach($this->_required_attributes as $attribute)
        {
            // If it exists and contains an empty string
            if(property_exists($this, $attribute) && empty($this->$attribute))
            {
                $this->_errors[] = '['.$attribute.'] is required.';
                return false;
            }
        }
        return true;
    }
    
    /*
     * Creates an array containing all the attributes with its assigned values.
     * 
     * @return array
     */
    protected function _AttributesArrayWithValue()
    {
        $attributes = array();
        
        foreach($this::$attributes as $attribute)
        {
            if(is_array($attribute))
            {
                foreach($attribute as $attr)
                {
                    if(property_exists($this, $attr))
                        $attributes[$attr] = $this->$attr;
                }
                // Skip to the next attribute
                continue;
            }
            if(property_exists($this, $attribute))
                $attributes[$attribute] = $this->$attribute;
        }
        return $attributes;
    }
}