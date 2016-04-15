<?php

class labelManager {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }
	
	public function getLabelById($id, $lang) {
		$resultats = $this->db->prepare("SELECT id, ".$lang." FROM Labels where id = :id");
        $resultats->execute(array(
            ":id" => $id
        ));
		if ($retour = $resultats->fetch(PDO::FETCH_ASSOC)) {
			if ($retour[$lang] == null) {
				$resultat = $this->db->prepare("SELECT id, fr as ".$lang." FROM Labels where id = :id");
				$resultat->execute(array(
					":id" => $id
				));
				$retour = $resultat->fetch(PDO::FETCH_ASSOC);
			}
		} else {
			$retour['id'] = $id;
			$retour[$lang] = "aucune valeur trouvée.";
		}
		return $retour;
	}
}
?>