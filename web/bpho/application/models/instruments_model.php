<?php

class Instruments_model extends CI_Model {

    function validateByName($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('Instruments');
        if($query->num_rows == 1) {
            return true;
        }
    }

    function validateByID($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('Instruments');
        if($query->num_rows == 1) {
            return true;
        }
    }

    function getInstrumentByName($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('Instruments');
        if($query->num_rows == 1){
            return $query->result_array();
        }
    }

    /**
     * return all the instruments of a user
     * @param $id : id of the user
     * @return array : retour[0] = 'id' => 1, 'name' => aaaaa
     */
    function getInstrumentsByUser($id) {
        $this->db->where('idUser', $id);
        $query = $this->db->get('User_instrument');
        $result = $query->result_array();
        $retour = array();
        $i = 0;
        foreach ($result as $link) {
            $this->db->where('id', $link['idInstrument']);
            $query = $this->db->get('Instruments');
            $instrument = $query->result_array();
            $retour[$i] = $instrument[0];
            $i++;
        }
        return $retour;
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

    function getInstruments($search = null, $limit_start = null, $limit_end = null) {
        $this->db->select('*');
        $this->db->from('Instruments');
        if($search) {
            $this->db->like('name', $search);
        }
        $this->db->order_by('name', 'Asc');
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
    function countInstruments($search_string=null)
    {
        $this->db->select('*');
        $this->db->from('Instruments');

        if($search_string){
            $this->db->like('name', $search_string);
        }

        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function addInstrument($name) {
        $data = array(
            'name' => $name
        );
        $this->db->insert('Instruments', $data);
        $this->db->where('name', $name);
        $query = $this->db->get('Instruments');
        $row = $query->result_array();
        return $row[0]['id'];

    }

    /**
     * Delete user
     * @param int $id - user id
     * @return boolean
     */
    function delete_instrument($id){
        $this->db->where('id', $id);
        $this->db->delete('Instruments');
    }
}

