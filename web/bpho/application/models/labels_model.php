<?php

class Labels_model extends CI_Model {

    public $lang = "fr";

    public function getLang(){
        return $this->lang;
    }

    public function setLang($lang){
        $this->lang = $lang;
    }

    /**
     * get label by id
     * @param $id : label id
     * @return mixed : label
     */
    function getLabelByID($id) {
        $this->db->select($this->getLang());
        $this->db->where('id', $id);
        $query = $this->db->get('Labels');
        $result = $query->result_array();
        if (isset($result[0])) {
            return $result[0][$this->getLang()];
        }
        return "Information manquante";
    }

    /**
     * search for labels based on input string
     * @param $search : string search
     * @return mixed : array of labels that contains the search string
     */
    function searchLabel($search) {
        $this->db->select('id');
        $this->db->select($this->getLang());
        $this->db->like($this->getLang(), $search);
        $query = $this->db->get('Labels');
        return $query->result_array();
    }

}