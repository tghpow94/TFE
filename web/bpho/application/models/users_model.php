<?php

require "db_connect.php";

class Users_model extends CI_Model {

    /**
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	function validate($user_name, $password)
	{
		$this->db->where('email', $user_name);
		$this->db->where('password', $password);
		$query = $this->db->get('Users');
		if($query->num_rows == 1) {
			$data = array(
				"dateLastConnect" => date("Y-m-d H:i:s")
			);
			$this->db->where('email', $user_name);
			$this->db->update('Users', $data);
			return true;
		}
		return false;
	}

	function passwordreset($mail) {
		$this->db->where('email', $mail);
		$query = $this->db->get('Users');
		if($query->num_rows < 1) {
			return false;
		} else {
			$rows = $query->result_array();
			$user = $rows[0];

			$code = $this->genererCode();

			$this->db->where('idUser', $user['id']);
			$this->db->delete('Activation');
			$dataActivation = array(
				"idUser" => $user['id'],
				"code" => $code,
				"date" => date("Y-m-d H:i:s"),
				"description" => "mot de passe oublié"
			);

			$this->db->insert('Activation', $dataActivation);

			$message = "Bonjour, <br><br>
						Vous avez demandé à réinitialiser votre mot de passe.<br><br>
						Cliquez sur le lien suivant pour finaliser la procédure : <a href='http://91.121.151.137/TFE/bpho/admin/passwordreset_final?code=".$code."'>http://91.121.151.137/TFE/bpho/admin/passwordreset_final?code=".$code."</a>";

			$to = $mail;
			$from = "thomaspicke2@gmail.com";
			$sujet = "BPHO : Réinitialisation de votre mot de passe";
			$entete = "From:" . $from . "\r\n";
			$entete .= "Content-Type: text/html; charset=utf-8\r\n";
			mail($to, $sujet, $message, $entete);

			return true;
		}
	}

	function checkCode($code, $description) {
		$this->db->where('code', $code);
		$this->db->where('description', $description);
		$query = $this->db->get('Activation');
		if ($query->num_rows == 1) {
			return true;
		}else {
			return false;
		}
	}

	function deleteActivationMdp($id, $description) {
		$this->db->where('idUser', $id);
		$this->db->where('description', $description);
		$this->db->delete('Activation');
	}

	function genererCode() {
		$characts    = 'abcdefghijklmnopqrstuvwxyz';
		$characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characts   .= '1234567890';
		$code_aleatoire      = '';

		for($i=0;$i < 10;$i++){
			$code_aleatoire .= substr($characts,rand()%(strlen($characts)),1);
		}
		return $code_aleatoire;
	}

	function getUserByName($user_name) {
		$this->db->where('email', $user_name);
		$query = $this->db->get('Users');
		if($query->num_rows == 1){
			return $query->result_array();
		}
	}
	
	function getUserByID($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('Users');
		if ($query->num_rows == 1) {
			return $query->result_array();
		}
	}

	function getUserDroitByEmail($email) {
		$this->db->where('email', $email);
		$query = $this->db->get('Users');
		if ($query->num_rows == 1) {
			$result = $query->result_array();
			$userID = $result[0]['id'];
			return $this->getUserDroit($userID);
		}
	}

	function getUserDroit($id) {
		$this->db->where('idUser', $id);
		$query = $this->db->get('User_right');
		if ($query->num_rows == 1) {
			$result = $query->result_array();
			return $result[0]['idRight'];
		}
	}

	function getUserInstrument($id) {
		$this->db->where('idUser', $id);
		$query = $this->db->get('User_instrument');
		if ($query->num_rows == 1) {
			$result = $query->result_array();
			return $result[0]['idInstrument'];
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

	function getActivationByCode($code) {
		$this->db->where('code', $code);
		$this->db->where('description', "mot de passe oublié");
		$query = $this->db->get('Activation');
		if($query->num_rows == 1 ) {
			$result = $query->result_array();
			return $result[0];
		}
		return false;
	}

	function updateMdp($password, $salt, $id) {
		$data = array(
			"password" => hash('sha256', $password.$salt)
		);
		$this->db->where('id', $id);
		$this->db->update('Users', $data);
	}

	/**
	 * Store the new user's data into the database
	 * @param $data : data of the new user
	 * @return bool - check the insert
	 */
	function create_member($data)
	{
		$salt = uniqid(mt_rand(), true);
		$this->db->where('email', $data['email']);
		$query = $this->db->get('Users');

		if($query->num_rows > 0){
			echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>';
			echo "Cette adresse email est déjà utilisée";
			echo '</strong></div>';
		}else{

			$new_member_insert_data = array(
				'email' => $data['email'],
				'password' => hash('sha256', $data['password'].$salt),
				'salt' => $salt,
				'name' => $data['name'],
				'firstName' => $data['firstName'],
				'phone' => $data['phone'],
				'dateRegister' => date("Y-m-d H:i:s")
			);
			$insert = $this->db->insert('Users', $new_member_insert_data);

			$this->db->where('email', $data['email']);
			$query = $this->db->get('Users');
			$result = $query->result_array();

			$new_member_insert_data_right = array(
				'idUser' => $result[0]['id'],
				'idRight' => $data['right'],
				'date' => date("Y-m-d H:i:s")
			);
			$insert = $this->db->insert('User_right', $new_member_insert_data_right);

			if ($data['instrument'] != "") {
				$this->addUserInstrument($data['instrument'], $result[0]['id']);
			}

			return $insert;
		}

	}

	/**
	 * update a user's data
	 * @param $id : user's id
	 * @param $data : new user's data
	 * @return bool : true if succes, false if fail
	 */
	function updateUser($id, $data) {
		//update user data
		$dataUser = array(
			'name' => $data['name'],
			'firstName' => $data['firstName'],
			'phone' => $data['phone']
		);
		$this->db->where('id', $id);
		$this->db->update('Users', $dataUser);

		//update instrument data
		$this->addUserInstrument($data['instrument'], $id);

		//update right data
		$dataRight = array(
			'idRight' => $data['right'],
			'date' => date("Y-m-d H:i:s")
		);
		$this->db->where('idUser', $id);
		$this->db->update('User_right', $dataRight);

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
	 * check if the user already has an instrument
	 * @param $id : user's id
	 * @return bool : true if user has an instrument, false if he hasn't
	 */
	function hasInstrument($id) {
		$this->db->where('idUser', $id);
		$query = $this->db->get('User_instrument');
		if($query->num_rows == 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * delete the link between the user and his instrument
	 * @param $id : user's id
	 */
	function deleteUserInstrument($id) {
		$this->db->where('idUser', $id);
		$this->db->delete('User_instrument');
	}

	/**
	 * ajoute un lien entre un instrument et un user, et ajoute l'instrument si il n'existe pas
	 * @param $instrument
	 * @param $idUser
	 */
	function addUserInstrument($instrumentName, $idUser) {
		$im = new Instruments_model();
		if ($im->validateByName($instrumentName)) {
			$idInstrument = $im->getInstrumentByName($instrumentName);
			$idInstrument = $idInstrument[0]['id'];
		} else {
			$idInstrument = $im->addInstrument($instrumentName);
		}

		$updateData = array(
			'idUser' => $idUser,
			'idInstrument' => $idInstrument
		);

		if ($this->hasInstrument($idUser)) {
			$this->db->where('idUser', $idUser);
			$this->db->update('User_instrument', $updateData);
		} else {
			$this->db->insert('User_instrument', $updateData);
		}
	}

	/**
	 * get all the users from db
	 * @param null $search
	 * @param $limit_start
	 * @param $limit_end
	 * @return mixed : array of all users
	 */
	function getUsers($search = null, $limit_start = null, $limit_end = null) {
		$this->db->select('*');
		$this->db->from('Users');
		if($search) {
			$this->db->or_like('name', $search);
			$this->db->or_like('firstName', $search);
		}
		$this->db->order_by('id', 'Asc');
		if ($limit_end && $limit_start)
			$this->db->limit($limit_start, $limit_end);
		$query = $this->db->get();

		return $query->result_array();
	}

	/**
	 * get all the users from db
	 * @param null $search
	 * @param $limit_start
	 * @param $limit_end
	 * @return mixed : array of all users
	 */
	function getUsersOrderByFirstName($search = null, $limit_start = null, $limit_end = null) {
		$this->db->select('*');
		$this->db->from('Users');
		if($search) {
			$this->db->or_like('name', $search);
			$this->db->or_like('firstName', $search);
		}
		$this->db->order_by('firstName', 'Asc');
		if ($limit_end && $limit_start)
			$this->db->limit($limit_start, $limit_end);
		$query = $this->db->get();

		return $query->result_array();
	}

	/**
	 * Count the number of rows
	 * @param int $search_string
	 * @return int
	 */
	function countUsers($search_string=null)
	{
		$this->db->select('*');
		$this->db->from('Users');

		if($search_string){
			$this->db->or_like('name', $search_string);
			$this->db->or_like('firstName', $search_string);
		}

		$query = $this->db->get();
		return $query->num_rows();
	}

	/**
	 * Delete user
	 * @param int $id - user id
	 * @return boolean
	 */
	function delete_user($id){
		$this->db->where('idUser', $id);
		$query = $this->db->get('User_right');
		$resultat = $query->result_array();
		$droit = $resultat[0]['idRight'];
		if ($droit == 1) {
			return false;
		} else {
			$this->db->where('id', $id);
			$this->db->delete('Users');
		}
	}
}

