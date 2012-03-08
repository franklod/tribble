<?php

class Trash_api_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getTrash()
    {
        $query = $this->db->get('trash');
        return $query->result();
    }

    function emptyTrash(){
        $this->db->simple_query('DELETE FROM tr_trash');
    }

    function putInTrash($trash_path){
        $this->db->insert('trash',array('trash_path'=>$trash_path));
    }

}

?>