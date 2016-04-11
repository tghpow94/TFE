<?php
/**
 * Fonction permettant la connexion à la base de donnée
 * @return PDO la base de donnée
 */
function connexionDb()

    $type = "mysql"
    $host = "91.121.151.137"
    $servername = "$type:host=$host";
    $username = "root"
    $password = "totopipo007"
    $dbname = "bpho"

    $db = new PDO("$servername;dbname=$dbname", $username, $password);

    return $db;
}