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

    function getFullLabelByID($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('Labels');
        if ($query->num_rows == 1) {
            $result = $query->result_array();
            return $result[0];
        }
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

    /**
     * Add a label in the label table
     * @param $fr : label FR
     * @param $nl : label NL
     * @param $en : label EN
     */
    function addLabel($fr, $nl, $en) {
        if (!$this->getLabelIdFullLang($fr,$nl,$en)) {
            $data = array(
                'fr' => $fr,
                'nl' => $nl,
                'en' => $en
            );
            $this->db->insert('Labels', $data);
        }
        return $this->getLabelIdFullLang($fr, $nl, $en);
    }

    /**
     * Add a label in the label table
     * @param $fr : label FR
     * @param $nl : label NL
     * @param $en : label EN
     */
    function addLabel2($fr, $nl, $en) {
        $data = array(
            'fr' => $fr,
            'nl' => $nl,
            'en' => $en
        );
        $this->db->insert('Labels', $data);

        return $this->db->insert_id();
    }

    function editLabel($id, $fr, $nl, $en) {
        $data = array(
            'fr' => $fr,
            'nl' => $nl,
            'en' => $en
        );
        $this->db->where('id', $id);
        $this->db->update('Labels', $data);
    }

    /**
     * return the label ID of the label $fr
     * @param $fr : label to look for
     */
    function getLabelId($fr) {
        $this->db->where('fr', $fr);
        $query = $this->db->get('Labels');
        $result = $query->result_array();
        return $result[0]['id'];
    }

    function getLabelIdFullLang($fr, $nl, $en) {
        $this->db->where('fr', $fr);
        $this->db->where('nl', $nl);
        $this->db->where('en', $en);
        $query = $this->db->get('Labels');
        $result = $query->result_array();
        return $result[0]['id'];
    }
}