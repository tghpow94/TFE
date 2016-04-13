<?php

class EventManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }
	
	public function getAllEvents() {
		$resultats = $this->db->query("SELECT * FROM Events ORDER BY startDate asc");
        $resultats->execute();

        $tabEvent = $resultats->fetchAll(PDO::FETCH_ASSOC);
		//foreach($tabEvent as $row){
			//$lm = new labelManager(connexionDb());
			//$label = $lm->getLabelById($row['title'], $lang);
			//$row['title'] = $label[$lang];
		//}
		
		return $tabEvent;
	}
}
?>