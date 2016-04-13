<?php

class EventManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }
	
	public function getLabelById(int $id, string $lang) {
		$resultats = $this->db->query("SELECT id, :lang FROM Labels where id = :id");
        $resultats->execute(array(
			":lang" => $lang,
            ":id" => $id
        ));
		$retour = $resultats->fetch(PDO::FETCH_ASSOC);
		return $retour;
	}
}
?>