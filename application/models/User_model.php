<?php

class User_model extends CI_Model {

    /**
     * Base table name
     * @var string 
     */
    protected $table_name;

    public function __construct() {
        parent::__construct();

        $this->table_name = 'users';
    }
    
    /**
     * 
     * This function help you to get data list from database
     * 
     * @return type
     */
    function users_list() {
        $users = $this->db->select('*')
                ->from($this->table_name)
                ->join('category', 'category.c_id = users.u_category_id')
                ->where('u_status',1)
                ->get();
        return $users->result();
    }
    
    /**
     * 
     * This function help you to add data in database
     * 
     * @param string $table_name
     * @param array $data
     * @return integer
     */
    function insert($table_name, $data) {
        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }
    
    /**
     * 
     * This function help you to update record without prepare query
     * 
     * @param string $table_name
     * @param array $data
     * @param array $where
     * @return integer
     */
    function update($table_name, $data, $where) {
        $this->db->update($table_name, $data, $where);
        return $this->db->affected_rows();
    }
    
    /**
     * 
     * This function help you to get single data from database
     * 
     * @return type
     */
    function getuser($id) {
        $users = $this->db->select('*')
                ->from($this->table_name)
                ->where('u_status',1)
                ->where('u_id',$id)
                ->get();
        return $users->result();
    }

}
