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
		if($query->num_rows == 1)
		{
			return true;
		}		
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

	function addUserInstrument($instrument, $idUser) {
		$im = new Instruments_model();
		if ($im->validateByName($instrument)) {
			$id = $im->getInstrumentByName($instrument);
			$new_intrument_link_data = array(
				'idUser' => $idUser,
				'idInstrument' => $id[0]['id']
			);
		} else {
			$id = $im->addInstrument($instrument);
			$new_intrument_link_data = array(
				'idUser' => $idUser,
				'idInstrument' => $id
			);
		}
		$this->db->insert('User_instrument', $new_intrument_link_data);
	}

	function getUsers($search = null, $limit_start, $limit_end) {
		$this->db->select('*');
		$this->db->from('Users');
		if($search) {
			$this->db->or_like('name', $search);
			$this->db->or_like('firstName', $search);
		}
		$this->db->order_by('id', 'Asc');
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
		$this->db->where('id', $id);
		$this->db->delete('Users');
	}
}

