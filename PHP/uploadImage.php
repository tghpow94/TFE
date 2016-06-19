<?php

$target_path  = "/home/site1/www/TFE/images/";
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

if(is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {

    $fileName = $target_path;
    $fileNameTab = explode(".", $fileName);
    unlink($fileNameTab[0].".jpg");
    unlink($fileNameTab[0].".jpeg");
    unlink($fileNameTab[0].".png");
    unlink($fileNameTab[0].".gif");

    $photo = $_FILES['uploadedfile']['tmp_name'];
    $typePhoto = $fileNameTab[1];
    $nomPhoto = basename( $_FILES['uploadedfile']['name']);

    $h = fopen("fichier.txt", "a");
    fwrite($h, $nomPhoto);
    fclose($h);

    $donnees=getimagesize($photo);
    $nouvelleLargeur = 350;
    $reduction = ( ($nouvelleLargeur * 100) / $donnees[0]);
    $nouvelleHauteur = ( ($donnees[1] * $reduction) / 100);
    if ($typePhoto == "jpg" || $typePhoto == "jpeg") {
        $image = imagecreatefromjpeg($photo);
    } elseif ($typePhoto == "png") {
        $image = imagecreatefrompng($photo);
    } elseif ($typePhoto == "gif") {
        $image = imagecreatefromgif($photo);
    }
    $image_mini = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur); //création image finale
    imagecopyresampled($image_mini, $image, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $donnees[0], $donnees[1]);//copie avec redimensionnement
    imagejpeg ($image_mini, $fileNameTab[0].".jpg");

}

/*if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {

    echo "The file ".  basename( $_FILES['uploadedfile']['name'])." has been uploaded";

} else {

    echo "There was an error uploading the file, please try again!";

}*/

?>