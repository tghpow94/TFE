<?php

class Events_model extends CI_Model {

    function getEventByTitle($name) {
        $lm = new Labels_model();
        $label = $lm->searchLabel($name);
        $this->db->where('title', $label[0]['id']);
        $query = $this->db->get('Events');
        if($query->num_rows == 1){
            return $query->result_array();
        }
    }

    /**
     * return the id of the event $title
     * @param $title : event's title
     * @return mixed : event's id
     */
    function getEventID() {
        return $this->db->insert_id();
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
            $result = $query->result_array();
            return $result[0];
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

    function getConcert() {
        $lm = new Labels_model();


        $this->db->where('idCategory', 2);
        $query = $this->db->get('Event_category');
        $response = $query->result_array();
        $ids = array();
        foreach($response as $item) {
            $ids[] = $item['idEvent'];
        }

        if(isset($ids[0])) {

            $this->db->where_in('id', $ids);

            $this->db->select('*');
            $this->db->from('Events');

            $this->db->order_by('startDate', 'Desc');
            $query = $this->db->get();
            $results = $query->result_array();
            foreach ($results as &$row) {
                $row['title'] = $lm->getLabelByID($row['title']);
                $row['description'] = $lm->getLabelByID($row['description']);
                $row['date'] = $row['startDate'];
            }
            return $results;
        } else {
            return null;
        }
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
        $event = $this->getEventByID($id);

        $this->db->where('id', $event['title']);
        $this->db->delete('Labels');
        $this->db->where('id', $event['description']);
        $this->db->delete('Labels');

        $this->db->where('idEvent', $id);
        $this->db->delete('Event_category');

        $this->db->where('idEvent', $id);
        $this->db->delete('Event_users');

        $this->db->where('id', $id);
        $this->db->delete('Events');
        $this->deleteEventUsers($id);
    }

    /**
     * add a new event
     * @param $data : event's data
     */
    function addEvent($data) {
        $insert = $this->db->insert('Events', $data);
        return true;
    }

    function getCategories() {
        $query = $this->db->get('Category');
        return $query->result_array();
    }

    function getEventCategory($id) {
        $this->db->select('idCategory');
        $this->db->where('idEvent', $id);
        $query = $this->db->get('Event_category');
        $result = $query->result_array();
        return $result[0]['idCategory'];
    }

    function addCategory($idEvent, $idCategory) {
        $this->db->where('idEvent', $idEvent);
        $this->db->delete('Event_category');
        $data = array(
            "idEvent" => $idEvent,
            "idCategory" => $idCategory
        );
        $this->db->insert('Event_category', $data);
    }

    function addLinkConcert($idRepet, $idConcert) {
        $data = array(
            "idConcert" => $idConcert,
            "idRepet" => $idRepet
        );
        $this->db->insert('Event_concert', $data);
    }

    function deleteLinkRepet($id) {
        $this->db->where('idRepet', $id);
        $this->db->delete('Event_concert');
    }

    function deleteLinkConcert($id) {
        $this->db->where('idConcert', $id);
        $this->db->delete('Event_concert');
    }

    function getEventConcert($id) {
        $this->db->where('idRepet', $id);
        $query = $this->db->get('Event_concert');
        if($query->num_rows > 0) {
            $result = $query->result_array();
            return $result[0]['idConcert'];
        }
    }

    function addEventUser($idEvent, $idUser) {
        $data = array(
            'idEvent' => $idEvent,
            'idUser' => $idUser
        );
        $insert = $this->db->insert('Event_users', $data);
        return $insert;
    }

    function getEventUsers($idEvent) {
        $this->db->where('idEvent', $idEvent);
        $query = $this->db->get('Event_users');
        return $query->result_array();
    }

    function deleteEventUsers($idEvent) {
        $this->db->where('idEvent', $idEvent);
        $this->db->delete('Event_users');
    }

    function updateEvent($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('Events', $data);

        //errors handler
        $report = array();
        $report['error'] = $this->db->_error_number();
        $report['message'] = $this->db->_error_message();
        if($report !== 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Fonction servant à charger une image et la mettre dans un dossier du serveur.
     * @param $repertoire : le répertoire d'arrivée de l'image.
     * @param $nom : le nom de l'image.
     * @param $image_raw : image à stocker
     */
    function uploadImage($repertoire, $nom, $image_raw) {
        $photo = $image_raw['image']['tmp_name'];
        if( !is_uploaded_file($photo) ) {
            var_dump($image_raw);
            exit("Le fichier est introuvable <br>");
        }
        // on vérifie maintenant l'extension
        $typePhoto = $image_raw['image']['type'];
        if( !strstr($typePhoto, 'jpg') && !strstr($typePhoto, 'jpeg') && !strstr($typePhoto, 'gif') && !strstr($typePhoto, 'png')) {
            exit("L'image n'est pas au bon format, les formats admis sont jpg, gif et png <br>");
        }
        // on copie le fichier dans le dossier de destination
        $nomPhoto = $nom.".jpg";

        $donnees=getimagesize($photo);
        $nouvelleLargeur = 350;
        $reduction = ( ($nouvelleLargeur * 100) / $donnees[0]);
        $nouvelleHauteur = ( ($donnees[1] * $reduction) / 100);
        if ($typePhoto == "image/jpg" || $typePhoto == "image/jpeg") {
            $image = imagecreatefromjpeg($photo);
        } elseif ($typePhoto == "image/png") {
            $image = imagecreatefrompng($photo);
        } elseif ($typePhoto == "image/gif") {
            $image = imagecreatefromgif($photo);
        }
        $image_mini = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur); //création image finale
        imagecopyresampled($image_mini, $image, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $donnees[0], $donnees[1]);//copie avec redimensionnement
        imagejpeg ($image_mini, $repertoire.$nomPhoto);
    }
}

