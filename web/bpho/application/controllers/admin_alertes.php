<?php
class Admin_alertes extends CI_Controller {

    /**
     * Responsible for auto load the model
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('events_model');
        $this->load->model('instruments_model');
        $this->load->model('users_model');
        $this->load->model('labels_model');
        $this->load->model('alertes_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }

    /*public function index()
    {

        $search_string = $this->input->post('search_string');
        if ($search_string == "") {
            $search_string = false;
        }

        //pagination settings
        $config['per_page'] = 5;
        $config['base_url'] = base_url().'admin/alertes';
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

            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
                $this->session->set_userdata($filter_session_data);
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            $data['count_alertes']= $this->alertes_model->countAlertes($search_string);
            $config['total_rows'] = $data['count_alertes'];

            //fetch sql data into arrays
            if($search_string){
                $data['alertes'] = $this->alertes_model->getAlertesByEventID(6);
            }else{
                $data['alertes'] = $this->alertes_model->getAlertesByEventID(6);
            }

        }else{

            //clean filter data inside section
            $filter_session_data['search_string_selected'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';

            //fetch sql data into arrays
            $data['count_alertes']= $this->alertes_model->countAlertes();
            $data['alertes'] = $this->alertes_model->getAlertesByEventID(6);
            $config['total_rows'] = $data['count_alertes'];

        }

        //initializate the panination helper
        $this->pagination->initialize($config);

        //load the view
        $data['main_content'] = 'admin/alertes/list';
        $this->load->view('includes/template', $data);

    }//index*/

    /*public function add() {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            //form validation
            $this->form_validation->set_rules('text', 'text', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run()) {

                $message = $this->input->post("text");

                $data_to_store = array(
                    'idEvent' => 6,
                    'text' => $message
                );

                //if the insert has returned true then we show the flash message
                if($this->alertes_model->addAlerte($data_to_store)){
                    $data['flash_message'] = TRUE;
                }else{
                    $data['flash_message'] = FALSE;
                }
            }
        }


        //load the view
        $data['main_content'] = 'admin/alertes/add';
        $this->load->view('includes/template', $data);
    }*/

    public function update() {
        //event id
        $id = $this->uri->segment(4);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $this->form_validation->set_rules('messageAlerte', 'messageAlerte', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            if ($this->form_validation->run()) {

                $message = $this->input->post("messageAlerte");

                $data_to_store = array(
                    'idEvent' => $id,
                    'text' => $message,
                    'date' => date("Y-m-d H:i:s")
                );

                //if the insert has returned true then we show the flash message
                if($this->alertes_model->addAlerte($data_to_store)){
                    $data['flash_message'] = TRUE;
                }else{
                    $data['flash_message'] = FALSE;
                }
                redirect('admin/alertes/update/'.$id.'');
            }
        }

        $data['id'] = $id;
        $event = $this->events_model->getEventByID($id);
        $fullDate2 = explode(" ", $event['startDate']);
        $date = explode("-", $fullDate2[0]);
        $annee = $date[0];
        $mois = $date[1];
        $jour = $date[2];
        $dateFinal = $jour."/".$mois."/".$annee." ".$fullDate2[1];
        $event['startDate'] = $dateFinal;

        $data['event'] = $event;
        $data['countAlertes'] = $this->alertes_model->countAlertesByEvent($id);
        $data['alertes'] = $this->alertes_model->getAlertesByEventID($id);

        if ($data['countAlertes'] > 0) {
            foreach ($data['alertes'] as &$alerte) {
                $fullDate2 = explode(" ", $alerte['date']);
                $date = explode("-", $fullDate2[0]);
                $annee = $date[0];
                $mois = $date[1];
                $jour = $date[2];
                $time = explode(":", $fullDate2[1]);
                $dateFinal = $jour . "/" . $mois . "/" . $annee . " " . $time[0] . ":" . $time[1];
                $alerte['date'] = $dateFinal;
            }
        }

        $data['main_content'] = 'admin/alertes/edit';
        $this->load->view('includes/template', $data);
    }

    public function delete() {

        $id = $this->uri->segment(4);
        $this->alertes_model->delete_alerte($id);
        redirect('admin/alertes');

    }

}