<?php


class Alertes_model extends CI_Model {

    function getAlertesByEventID($idEvent) {
        $this->db->where('idEvent', $idEvent);
        $this->db->order_by('date', 'Desc');
        $query = $this->db->get('Alerts');
        if($query->num_rows > 0) {
            return $query->result_array();
        }
    }

    function countAlertes($search = null) {

        $this->db->select('*');
        $this->db->from('Alerts');

        $query = $this->db->get();
        return $query->num_rows();
    }

    function addAlerte($data) {
        $this->db->insert('Alerts', $data);
    }

    function updateAlerte($data) {
        $this->db->where('id', 1);
        $this->db->update('Alerts', $data);
    }

    function delete_alerte($id) {
        $this->db->where('id', $id);
        $this->db->delete('Alerts');
    }

    function countAlertesByEvent($id) {
        $this->db->where('idEvent', $id);
        $query = $this->db->get('Alerts');
        return $query->num_rows();
    }

}