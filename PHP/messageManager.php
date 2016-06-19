<?php

class MessageManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
        $ini = $this->db->prepare("SET NAMES 'utf8'");
        $ini->execute(array());
    }

    public function getMessages() {
        $resultats = $this->db->prepare("SELECT * FROM Messages ORDER BY date DESC");
        $resultats->execute(array(
        ));

        $tabMessages = $resultats->fetchAll(PDO::FETCH_ASSOC);

        if(isset($tabMessages[0]['id'])) {

            foreach ($tabMessages as &$message) {
                $um = new userManager(connexionDb());
                $user = $um->getUser($message['idUser']);
                $message['name'] = $user['name'];
                $message['firstName'] = $user['firstName'];
            }

            return $tabMessages;
        }
        return null;
    }

    public function countMessages() {
        $resultats = $this->db->prepare("SELECT id FROM Messages");
        $resultats->execute(array(
        ));

        $tabMessages = $resultats->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach($tabMessages as $message) {
            $i++;
        }
        $retour['nb'] = $i;

        return $retour;

    }

    public function addMessage($idUser, $text) {
        $resultats = $this->db->prepare("INSERT INTO Messages (idUser, date, text) VALUES (:idUser, :date, :text)");
        $resultats->execute(array(
            ":idUser" => $idUser,
            ":date" => date("Y-m-d H:i:s"),
            ":text" => $text
        ));
    }

}
?>