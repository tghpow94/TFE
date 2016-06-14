<?php

class EventManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
        $ini = $this->db->prepare("SET NAMES 'utf8'");
        $ini->execute(array());
    }

    public function getAllEvents($offset, $lang, $type, $idUser) {

        $date = date("Y-m-d H:i:s");
        $newDate = date("Y-m-d H:i:s", strtotime("$date -$offset month"));
        if ($type == "general") {
            $resultats = $this->db->prepare("SELECT * FROM Events where startDate > :newDate  ORDER BY startDate");
            $resultats->execute(array(
                ":newDate" => $newDate
            ));
        } else {
            $resultats = $this->db->prepare("SELECT * FROM Events where startDate > :newDate AND id IN (SELECT idEvent FROM Event_users WHERE Event_users.idEvent = Events.id AND idUser = :idUser)  ORDER BY startDate");
            $resultats->execute(array(
                ":newDate" => $newDate,
                ":idUser" => $idUser
            ));
        }

        $tabEvent = $resultats->fetchAll(PDO::FETCH_ASSOC);
        $tab = array();
        $lm = new labelManager(connexionDb());
        foreach ($tabEvent as $row) {
            $event = $row;
            $id = $event['id'];
            $cityCode = $event['cityCode'];
            foreach ($event as &$elem) {
                if (is_numeric($elem)) {
                    $label = $lm->getLabelById($elem, $lang);
                    $elem = $label[$lang];
                }
            }
            $event['id'] = $id;
            $event['cityCode'] = $cityCode;
            $tab[] = $event;
        }
        return $tab;
    }

    public function getEventAlerts($idEvent, $idUser) {
        $resultats = $this->db->prepare("SELECT * FROM Alerts WHERE idEvent = :idEvent AND idEvent IN (SELECT idEvent FROM Event_users WHERE Alerts.idEvent = Event_users.idEvent AND idUser = :idUser)  ORDER BY date DESC ");
        $resultats->execute(array(
            ":idEvent" => $idEvent,
            ":idUser" => $idUser
        ));

        $tabAlerts = $resultats->fetchAll(PDO::FETCH_ASSOC);
        return $tabAlerts;
    }
}

?>