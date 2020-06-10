<?php

class Category_model extends CI_Model {

    /**
     * Base table name
     * @var string 
     */
    protected $table_name;

    public function __construct() {
        parent::__construct();

        $this->table_name = 'category';
    } 
    
	function categories_list(){
		$categories=$this->db->select('*')
			 ->from($this->table_name)
			 ->get();
		return $categories->result();
	}
}
