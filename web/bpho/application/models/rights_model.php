<?php

class Rights_model extends CI_Model {

    function getRightByName($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('Rights');
        if($query->num_rows == 1){
            return $query->result_array();
        }
    }

    function getRightByID($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('Rights');
        if ($query->num_rows == 1) {
            return $query->result();
        }
    }


    /**
     * Serialize the session data stored in the database,
     * store it in a new array and return it to the controller
     * @return array
     */
    function get_db_session_data()
    {
        $query = $this->db->select('user_data')->get('ci_sessions');
        $user = array(); /* array to store the user data we fetch */
        foreach ($query->result() as $row)
        {
            $udata = unserialize($row->user_data);
            /* put data in array using username as key */
            $user['user_name'] = $udata['user_name'];
            $user['is_logged_in'] = $udata['is_logged_in'];
        }
        return $user;
    }

    function getRights($search = null, $limit_start = null, $limit_end = null) {
        $this->db->select('*');
        $this->db->from('Rights');
        if($search) {
            $this->db->like('name', $search);
        }
        $this->db->order_by('id', 'Asc');
        if($limit_start && $limit_end){
            $this->db->limit($limit_start, $limit_end);
        }
        $query = $this->db->get();

        return $query->result_array();
    }

    /**
     * Count the number of rows
     * @param int $search_string
     * @return int
     */
    function countRights($search_string=null)
    {
        $this->db->select('*');
        $this->db->from('Rights');

        if($search_string){
            $this->db->like('name', $search_string);
        }

        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * Delete user
     * @param int $id - user id
     * @return boolean
     */
    function delete_right($id){
        $this->db->where('id', $id);
        $this->db->delete('Rights');
    }
}

