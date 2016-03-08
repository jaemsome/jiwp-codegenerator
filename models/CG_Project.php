<?php

class CG_Project extends CG_ActiveRecord
{
    public static $attributes = array(
        'id', 'user_id', 'name', 'processor_type', 'frequency'
    );
    public $id;
    public $user_id;
    public $name;
    public $processor_type;
    public $frequency;
    
    /*
     * Name of the database table used for storing project data
     */
    protected $_table_name = 'wp_kmi_project';
    
    /*
     * Create new instance of the class
     * 
     * @return CG_Project object
     */
    public static function model()
    {
        return new self;
    }
    
    /*
     * Get all the project records for a specific user
     * 
     * @param user ID
     * 
     * @return array
     */
    public function FindAllByUserID($user_id=0)
    {
        global $wpdb;
        
        $projects_arr = array();
        
        $query = "SELECT * FROM ".$this->_table_name." WHERE user_id = '%s' ORDER BY name";
        $query = $wpdb->prepare($query, $user_id);
        
        $user_projects = $wpdb->get_results($query);
        
        if(count($user_projects) > 0)
        {
            foreach($user_projects as $proj)
            {
                $project = new self;
                // Assign the retrieved attributes into the new project object
                foreach($this::$attributes as $attribute)
                {
                    $project->$attribute = $proj->$attribute;
                }
                // Put the new project object into the array
                $projects_arr[$project->id] = $project;
            }
        }
        return $projects_arr;
    }
}