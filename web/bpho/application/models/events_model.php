<?php

class Events_model extends CI_Model {

    function getEventByName($name) {
        $this->db->where('email', $name);
        $query = $this->db->get('Users');
        if($query->num_rows == 1){
            return $query->result_array();
        }
    }

    /**
     * get an event based on its id
     * @param $id : event's id
     * @return mixed : event
     */
    function getEventByID($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('Events');
        if ($query->num_rows == 1) {
            return $query->result_array();
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

    /**
     * get all the users from db
     * @param null $search
     * @param $limit_start
     * @param $limit_end
     * @return mixed : array of all users
     */
    function getEvents($search = null, $limit_start, $limit_end) {
        $lm = new Labels_model();
        if($search) {
            $labels = $lm->searchLabel($search);
            $labelIDs = array();
            $i = 0;
            foreach ($labels as $label) {
                $labelIDs[$i] = $label['id'];
                $i++;
            }
            $this->db->where_in('title', $labelIDs);
        }

        $this->db->select('*');
        $this->db->from('Events');

        $this->db->order_by('startDate', 'Desc');
        $this->db->limit($limit_start, $limit_end);
        $query = $this->db->get();
        $results = $query->result_array();
        foreach ($results as &$row) {
            $row['title'] = $lm->getLabelByID($row['title']);
            $row['description'] = $lm->getLabelByID($row['description']);
            $row['date'] = $row['startDate'];
        }

        return $results;
    }

    /**
     * Count the number of rows
     * @param int $search
     * @return int
     */
    function countEvents($search = null)
    {
        if($search) {
            $lm = new Labels_model();
            $labels = $lm->searchLabel($search);
            $labelIDs = array();
            $i = 0;
            foreach ($labels as $label) {
                $labelIDs[$i] = $label['id'];
                $i++;
            }
            $this->db->where_in('title', $labelIDs);
        }

        $this->db->select('*');
        $this->db->from('Events');

        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * Delete user
     * @param int $id - user id
     * @return boolean
     */
    function delete_Event($id){
        $this->db->where('id', $id);
        $this->db->delete('Events');
    }
}

