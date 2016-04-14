<?php

class EventManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }
	
	public function getAllEvents($offset, $lang) {
		$date = date("Y-m-d H:i:s");
        $newDate = date("Y-m-d H:i:s", strtotime("$date -$offset month"));
		$resultats = $this->db->prepare("SELECT * FROM Events where startDate > :newDate ORDER BY startDate");
        $resultats->execute(array(
			":newDate" => $newDate
		));

        $tabEvent = $resultats->fetchAll(PDO::FETCH_ASSOC);
		$tab = array();
		$lm = new labelManager(connexionDb());
		foreach($tabEvent as $row){
			$event = $row;
			foreach($event as &$elem) {
				if(is_numeric($elem)) {
					$label = $lm->getLabelById($elem, $lang);
					$elem = $label[$lang];
				}
			}
			//$label = $lm->getLabelById($event['title'], $lang);
			//$event['title'] = $label[$lang];
			$tab[] = $event;
		}
		return $tab;
	}
}
?>