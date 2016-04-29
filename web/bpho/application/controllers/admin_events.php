<?php
class Admin_events extends CI_Controller {

    /**
     * Responsible for auto load the model
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('events_model');
        $this->load->model('Labels_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

    /**
     * Load the main view with all the current model model's data.
     * @return void
     */
    public function index()
    {

        //all the posts sent by the view
        $search_string = $this->input->post('search_string');
        if ($search_string == "") {
            $search_string = false;
        }

        //pagination settings
        $config['per_page'] = 10;
        $config['base_url'] = base_url().'admin/events';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        }

        //we must avoid a page reload with the previous session data
        //if any filter post was sent, then it's the first time we load the content
        //in this case we clean the session filter data
        //if any filter post was sent but we are in some page, we must load the session data

        //filtered && || paginated
        if($search_string !== false || $this->uri->segment(3) == true){

            /*
            The comments here are the same for line 79 until 99

            if post is not null, we store it in session data array
            if is null, we use the session data already stored
            we save order into the the var to load the view with the param already selected       
            */

            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
                $this->session->set_userdata($filter_session_data);
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;
            
            $data['count_events']= $this->events_model->countEvents($search_string);
            $config['total_rows'] = $data['count_events'];

            //fetch sql data into arrays
            if($search_string){
                $data['events'] = $this->events_model->getEvents($search_string, $config['per_page'], $limit_end);
            }else{
                $data['events'] = $this->events_model->getEvents('', $config['per_page'],$limit_end);
            }

        }else{

            //clean filter data inside section
            $filter_session_data['search_string_selected'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';

            //fetch sql data into arrays
            $data['count_events']= $this->events_model->countEvents();
            $data['events'] = $this->events_model->getEvents('', $config['per_page'],$limit_end);
            $config['total_rows'] = $data['count_events'];

        }

        //initializate the panination helper 
        $this->pagination->initialize($config);

        //load the view
        $data['main_content'] = 'admin/events/list';
        $this->load->view('includes/template', $data);

    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
            $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]');
            $this->form_validation->set_rules('password2', 'password2', 'trim|required|min_length[8]');
            $this->form_validation->set_rules('name', 'name', 'trim|required');
            $this->form_validation->set_rules('firstName', 'firstName', 'trim|required');
            $this->form_validation->set_rules('right', 'right', 'trim|required');
            $this->form_validation->set_rules('instrument', 'instrument', '');
            $this->form_validation->set_rules('phone', 'phone', '');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run() && $this->input->post('password') == $this->input->post('password2')) {
                $trash = array("/", ".");
                $data_to_store = array(
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password'),
                    'name' => $this->input->post('name'),
                    'firstName' => $this->input->post('firstName'),
                    'right' => $this->input->post('right'),
                    'instrument' => $this->input->post('instrument'),
                    'phone' => str_replace($trash, "",$this->input->post('phone'))
                );
                //if the insert has returned true then we show the flash message
                if($this->users_model->create_member($data_to_store)){
                    $data['flash_message'] = TRUE;
                }else{
                    $data['flash_message'] = FALSE;
                }
            }
        }

        //load the view
        $data['main_content'] = 'admin/events/add';
        $this->load->view('includes/template', $data);
    }

    /**
     * Update item by his id
     */
    public function update()
    {
        //event id
        $id = $this->uri->segment(4);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('name', 'name', 'required');
            $this->form_validation->set_rules('firstName', 'firstName', 'required');
            $this->form_validation->set_rules('right', 'right', 'required');
            $this->form_validation->set_rules('instrument', 'instrument', 'required');
            $this->form_validation->set_rules('phone', 'phone', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $trash = array("/", ".");
                $data_to_store = array(
                    'name' => $this->input->post('name'),
                    'firstName' => $this->input->post('firstName'),
                    'right' => $this->input->post('right'),
                    'instrument' => $this->input->post('instrument'),
                    'phone' => str_replace($trash, "",$this->input->post('phone'))
                );
                //if the insert has returned true then we show the flash message
                if($this->users_model->updateUser($id, $data_to_store) == TRUE){
                    $this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/users/update/'.$id.'');

            }//validation run

        }

        //rights
        $data['rights'] = $this->rights_model->getRights();

        //user data
        $user = $this->users_model->getUserByID($id);
        $user[0]['idRight'] = $this->users_model->getUserDroit($id);
        $user[0]['idInstrument'] = $this->users_model->getUserInstrument($id);
        $data['user'] = $user;

        //instruments
        $data['instruments'] = $this->instruments_model->getInstruments();
        $data['userInstrument'] = $this->instruments_model->getInstrumentByUser($user[0]['id']);

        //load the view
        $data['main_content'] = 'admin/events/edit';
        $this->load->view('includes/template', $data);

    }

    /**
     * Delete event by his id
     */
    public function delete()
    {
        //product id 
        $id = $this->uri->segment(4);
        $this->events_model->delete_event($id);
        redirect('admin/events');
    }

}