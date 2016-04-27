<?php
  define('HOST','localhost');
  define('USER','root');
  define('PASS','poulet77');
  define('DB','projetIntegration');
  

$con = mysqli_connect(HOST,USER,PASS,DB);



$username = $_POST['UserName'];
$password = $_POST['Mdp'];
$password= hash("sha256", $password);
$userId=NULL;
$response["error"] ="erreur";

$sql = "select * from user where UserName = '".$username."' AND Mdp = '".$password. "'";

	$query = mysqli_query($con,$sql);
		$row = mysqli_fetch_assoc($query);
		$userId = $row['id'];
		$userNameBdd = $row['UserName'];
		$passwordBdd= $row['Mdp'];
		$emailBdd= $row['email'];
		
	
$sql2 = "select * from user_droit where id_User = '".$userId."' ";

	$query2 = mysqli_query($con,$sql2);
		$row2 = mysqli_fetch_assoc($query2);	
		$droit=$row2['id_Droits'];
		
		
		if($userId!=NULL){
			$response["error"] ="FALSE";
			$response["id"] = $userId;
			$response["UserName"]= $userNameBdd;
			$response["Mdp"] = $passwordBdd;
			$response["email"]= $emailBdd;
			$response["droit"]= $droit;
			
			$sql = "update user set DateLastConnect = NOW() where UserName = '".$userNameBdd."'";
			$query = mysqli_query($con, $sql);
        
			echo json_encode($response);
		}
		
?>
