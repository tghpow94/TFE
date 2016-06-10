<?php

class User extends CI_Controller {

    /**
    * Check if the user is logged in, if he's not, 
    * send him to the login page
    * @return void
    */	
	function index()
	{
		if($this->session->userdata('is_logged_in')){
			redirect('admin/users');
        }else{
        	$this->load->view('admin/login');	
        }
	}

    /**
    * encript the password 
    * @return mixed
    */	
    function __encrip_password($password, $salt) {
        return hash('sha256', $password.$salt);
    }	

    /**
    * check the username and the password with the database
    * @return void
    */
	function validate_credentials()
	{	

		$this->load->model('Users_model');

		$user_name = $this->input->post('user_name');
		$user = $this->Users_model->getUserByName($user_name);
		$password = $this->__encrip_password($this->input->post('password'), $user[0]['salt']);

		$is_valid = $this->Users_model->validate($user_name, $password);
		if($is_valid) {
			$userDroit = $this->Users_model->getUserDroitByEmail($user_name);
			if($userDroit <= 2 && $userDroit != null) {

				$data = array(
					'user_name' => $user_name,
					'is_logged_in' => true
				);
				$this->session->set_userdata($data);
				$this->Users_model->deleteActivationMdp($user[0]['id'], "mot de passe oublié");
				redirect('admin/users');
			} else {
				$data['droit_error'] = TRUE;
				$this->load->view('admin/login', $data);
			}
		} else {
			$data['message_error'] = TRUE;
			$this->load->view('admin/login', $data);	
		}
	}	

    /**
    * The method just loads the signup view
    * @return void
    */
	function signup()
	{
		$this->load->view('admin/signup_form');	
	}


	

    /**
    * Create new user and store it in the database
    * @return void
    */	
	function create_member()
	{
		$this->load->library('form_validation');
		
		// field name, error message, validation rules
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/signup_form');
		}
		
		else
		{			
			$this->load->model('Users_model');
			
			if($query = $this->Users_model->create_member())
			{
				$this->load->view('admin/signup_successful');			
			}
			else
			{
				$this->load->view('admin/signup_form');			
			}
		}
		
	}

	function passwordreset() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('mail', 'mail', 'trim|required|valid_email');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

		if($this->form_validation->run() == FALSE) {
			$this->load->view('admin/passwordreset');
		} else {
			$mail = $this->input->post("mail");
			$this->load->model('Users_model');

			if($query = $this->Users_model->passwordreset($mail)) {
				$this->load->view('admin/passwordreset_successful');
			} else {
				$this->load->view('admin/passwordreset');
			}
		}
	}

	function passwordreset_final() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('passwordConfirm', 'passwordConfirm', 'trim|required|matches[password]|min_length[8]');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

		$this->load->model('Users_model');
		if (isset($_GET['code'])) {
			$code = $_GET['code'];
			$this->session->set_flashdata('code', $code);
		} else {
			$code = $this->session->flashdata('code');
		}

		$checkCode = $this->Users_model->checkCode($code, "mot de passe oublié");


		if ($this->form_validation->run() == FALSE) {
			if ($checkCode) {
				$this->load->view('admin/passwordreset_final');
			} else {
				$this->load->view('admin/wrongCode');
			}
		} else {
			if ($checkCode) {
				$activation = $this->Users_model->getActivationByCode($code);
				$user = $this->Users_model->getUserByID($activation['idUser']);

				$password = $this->input->post("password");

				$this->Users_model->deleteActivationMdp($user[0]['id'], "mot de passe oublié");
				$this->Users_model->updateMdp($password, $user[0]['salt'], $user[0]['id']);
				$this->load->view('admin/login');
			} else {
				$this->load->view('admin/wrongCode');
			}
		}
	}
	
	/**
    * Destroy the session, and logout the user.
    * @return void
    */		
	function logout()
	{
		$this->session->sess_destroy();
		redirect('admin');
	}

}