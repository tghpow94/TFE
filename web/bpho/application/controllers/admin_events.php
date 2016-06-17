<?php
class Admin_events extends CI_Controller {

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

    public function add() {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            //form validation
            $this->form_validation->set_rules('titleFR', 'titleFR', 'trim|required');
            $this->form_validation->set_rules('titleNL', 'titleNL', 'trim');
            $this->form_validation->set_rules('titleEN', 'titleEN', 'trim');
            $this->form_validation->set_rules('descriptionFR', 'descriptionFR', 'trim');
            $this->form_validation->set_rules('descriptionNL', 'descriptionNL', 'trim');
            $this->form_validation->set_rules('descriptionEN', 'descriptionEN', 'trim');
            $this->form_validation->set_rules('categorie', 'categorie', '');
            $this->form_validation->set_rules('date', 'date', '');
            $this->form_validation->set_rules('city', 'city', 'trim');
            $this->form_validation->set_rules('cityCode', 'cityCode', 'trim');
            $this->form_validation->set_rules('address', 'address', 'trim');
            $this->form_validation->set_rules('addressInfos', 'addressInfos', 'trim');
            $this->form_validation->set_rules('price', 'price', 'trim');
            $this->form_validation->set_rules('reservation', 'reservation', 'trim');
            $this->form_validation->set_rules('users', 'users', '');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run()) {

                $this->input->post('titleFR') != "" ? $titleFR = $this->input->post('titleFR') : $titleFR = null;
                $this->input->post('titleNL') != "" ? $titleNL = $this->input->post('titleNL') : $titleNL = null;
                $this->input->post('titleEN') != "" ? $titleEN = $this->input->post('titleEN') : $titleEN = null;

                $this->input->post('descriptionFR') != "" ? $descriptionFR = $this->input->post('descriptionFR') : $descriptionFR = null;
                $this->input->post('descriptionNL') != "" ? $descriptionNL = $this->input->post('descriptionNL') : $descriptionNL = null;
                $this->input->post('descriptionEN') != "" ? $descriptionEN = $this->input->post('descriptionEN') : $descriptionEN = null;

                $titleID = $this->labels_model->addLabel2($titleFR, $titleNL, $titleEN);
                $descriptionID = $this->labels_model->addLabel2($descriptionFR, $descriptionNL, $descriptionEN);

                $fullDate = explode(' ', $this->input->post('date'));
                $dateArray = explode('/', $fullDate[0]);
                $heureArray = explode(':', $fullDate[1]);
                $jour = $dateArray[0];
                $mois = $dateArray[1];
                $annee = $dateArray[2];
                $heure = $heureArray[0];
                $minute = $heureArray[1];
                $seconde = "00";
                $finalDate = $annee.'-'.$mois.'-'.$jour.' '.$heure.':'.$minute.':'.$seconde;

                $this->input->post('reservation') != "" ? $reservation = $this->input->post('reservation') : $reservation = "http://www.bpho.be/concerts/";

                $this->input->post('cityCode') == 0 ? $cityCode = "" : $cityCode = $this->input->post('cityCode');

                $data_to_store = array(
                    'title' => $titleID,
                    'description' => $descriptionID,
                    'startDate' => $finalDate,
                    'endDate' => $finalDate,
                    'city' => $this->input->post('city'),
                    'cityCode' => $cityCode,
                    'addressInfos' => $this->input->post('addressInfos'),
                    'price' => $this->input->post('price'),
                    'reservation' => $reservation,
                    'address' => $this->input->post('address')
                );
                //if the insert has returned true then we show the flash message
                if($this->events_model->addEvent($data_to_store)){
                    $data['flash_message'] = TRUE;
                }else{
                    $data['flash_message'] = FALSE;
                }

                $eventID = $this->events_model->getEventID();
                if(isset($_FILES['image']) && $_FILES['image']['tmp_name'] != "") {
                    $this->events_model->uploadImage("../images/", "e".$eventID, $_FILES);
                } else {
                    copy("../images/no-image.jpg", "../images/e".$eventID.".jpg");
                }

                $this->events_model->addCategory($eventID, $this->input->post("categorie"));

                if($this->input->post("categorie") == 1 && $this->input->post("concert") > 0) {
                    $this->events_model->addLinkConcert($eventID, $this->input->post("concert"));
                }

                if (isset($_POST['users'][0])) {
                    $musiciens = $_POST['users'];
                    $this->events_model->deleteEventUsers($eventID);
                    foreach ($musiciens as $musicien) {
                        $this->events_model->addEventUser($eventID, $musicien);
                    }
                }
                $data['test'] = true;
            }
        }

        $events = $this->events_model->getConcert();

        $users = $this->users_model->getUsersOrderByFirstName2();
        foreach($users as &$user) {
            if($this->users_model->hasInstrument($user['id'])) {
                $instruments = $this->instruments_model->getInstrumentsByUser($user['id']);
                $user['instruments'] = $instruments;
            } else {
                $user['instruments'] = "";
            }
        }
        $data['users'] = $users;
        $data['concerts'] = $events;

        $data['categories'] = $this->events_model->getCategories();

        //load the view
        $data['main_content'] = 'admin/events/add';
        $this->load->view('includes/template', $data);
    }

    /**
     * Update item by his id
     */
    public function update() {
        //event id
        $id = $this->uri->segment(4);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            //form validation
            $this->form_validation->set_rules('titleFR', 'titleFR', 'trim|required');
            $this->form_validation->set_rules('titleNL', 'titleNL', 'trim');
            $this->form_validation->set_rules('titleEN', 'titleEN', 'trim');
            $this->form_validation->set_rules('descriptionFR', 'descriptionFR', 'trim');
            $this->form_validation->set_rules('descriptionNL', 'descriptionNL', 'trim');
            $this->form_validation->set_rules('descriptionEN', 'descriptionEN', 'trim');
            $this->form_validation->set_rules('date', 'date', '');
            $this->form_validation->set_rules('categorie', 'categorie', '');
            $this->form_validation->set_rules('city', 'city', 'trim');
            $this->form_validation->set_rules('cityCode', 'cityCode', 'trim');
            $this->form_validation->set_rules('address', 'address', 'trim');
            $this->form_validation->set_rules('addressInfos', 'addressInfos', 'trim');
            $this->form_validation->set_rules('price', 'price', 'trim');
            $this->form_validation->set_rules('reservation', 'reservation', 'trim');
            $this->form_validation->set_rules('users', 'users', '');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run()) {

                $this->input->post('titleFR') != "" ? $titleFR = $this->input->post('titleFR') : $titleFR = null;
                $this->input->post('titleNL') != "" ? $titleNL = $this->input->post('titleNL') : $titleNL = null;
                $this->input->post('titleEN') != "" ? $titleEN = $this->input->post('titleEN') : $titleEN = null;

                $this->input->post('descriptionFR') != "" ? $descriptionFR = $this->input->post('descriptionFR') : $descriptionFR = null;
                $this->input->post('descriptionNL') != "" ? $descriptionNL = $this->input->post('descriptionNL') : $descriptionNL = null;
                $this->input->post('descriptionEN') != "" ? $descriptionEN = $this->input->post('descriptionEN') : $descriptionEN = null;

                $event = $this->events_model->getEventByID($id);
                $titleID = $event['title'];
                $descriptionID = $event['description'];

                $this->labels_model->editLabel($titleID, $titleFR, $titleNL, $titleEN);
                $this->labels_model->editLabel($descriptionID, $descriptionFR, $descriptionNL, $descriptionEN);

                $fullDate = explode(' ', $this->input->post('date'));
                $dateArray = explode('/', $fullDate[0]);
                $heureArray = explode(':', $fullDate[1]);
                $jour = $dateArray[0];
                $mois = $dateArray[1];
                $annee = $dateArray[2];
                $heure = $heureArray[0];
                $minute = $heureArray[1];
                $seconde = "00";
                $finalDate = $annee.'-'.$mois.'-'.$jour.' '.$heure.':'.$minute.':'.$seconde;

                $this->input->post('reservation') != "" ? $reservation = $this->input->post('reservation') : $reservation = "http://www.bpho.be/concerts/";

                $data_to_store = array(
                    'title' => $titleID,
                    'description' => $descriptionID,
                    'startDate' => $finalDate,
                    'endDate' => $finalDate,
                    'city' => $this->input->post('city'),
                    'cityCode' => $this->input->post('cityCode'),
                    'addressInfos' => $this->input->post('addressInfos'),
                    'price' => $this->input->post('price'),
                    'reservation' => $reservation,
                    'address' => $this->input->post('address')
                );

                //if the insert has returned true then we show the flash message
                if($this->events_model->updateEvent($id, $data_to_store) == TRUE){
                    $this->session->set_flashdata('flash_message', 'updated');
                } else {
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }

                if(isset($_FILES['image']) && $_FILES['image']['tmp_name'] != "") {
                    $this->events_model->uploadImage("../images/", "e".$id, $_FILES);
                }

                $this->events_model->addCategory($id, $this->input->post("categorie"));

                $oldCat = $this->input->post("oldCategory");
                $newCat = $this->input->post("categorie");

                if($oldCat == 1 && $newCat == 2) {
                    $this->events_model->deleteLinkRepet($id);
                } elseif($oldCat == 1 && $newCat == 1) {
                    $this->events_model->deleteLinkRepet($id);
                    if($this->input->post("categorie") == 1 && $this->input->post("concert") > 0) {
                        $this->events_model->addLinkConcert($id, $this->input->post("concert"));
                    }
                } elseif($oldCat == 2 && $newCat == 1) {
                    $this->events_model->deleteLinkConcert($id);
                    if($this->input->post("categorie") == 1 && $this->input->post("concert") > 0) {
                        $this->events_model->addLinkConcert($id, $this->input->post("concert"));
                    }
                }

                if (isset($_POST['users'][0])) {
                    $musiciens = $_POST['users'];
                    $this->events_model->deleteEventUsers($id);
                    foreach ($musiciens as $musicien) {
                        $this->events_model->addEventUser($id, $musicien);
                    }
                } else {
                    $this->events_model->deleteEventUsers($id);
                }

                redirect('admin/events/update/'.$id.'');

            }

        }

        $event = $this->events_model->getEventByID($id);
        $fullDate2 = explode(" ", $event['startDate']);
        $date = explode("-", $fullDate2[0]);
        $annee = $date[0];
        $mois = $date[1];
        $jour = $date[2];
        $time = explode(":", $fullDate2[1]);
        $dateFinal = $jour."/".$mois."/".$annee." ".$time[0].":".$time[1];
        $event['startDate'] = $dateFinal;

        $data['event'] = $event;

        $title = $this->labels_model->getFullLabelByID($event['title']);
        $data['event']['titleFR'] = $title['fr'];
        $data['event']['titleNL'] = $title['nl'];
        $data['event']['titleEN'] = $title['en'];

        $description = $this->labels_model->getFullLabelByID($event['description']);
        $data['event']['descriptionFR'] = $description['fr'];
        $data['event']['descriptionNL'] = $description['nl'];
        $data['event']['descriptionEN'] = $description['en'];

        $data['event']['categorie'] = $this->events_model->getEventCategory($id);
        $data['categories'] = $this->events_model->getCategories();
        $concerts = $this->events_model->getConcert();
        $data['concerts'] = $concerts;

        if($data['event']['categorie'] == 1) {
            $data['event']['concert'] = $this->events_model->getEventConcert($id);
        } else {
            $data['event']['concert'] = 0;
        }

        $users = $this->users_model->getUsersOrderByFirstName();
        foreach($users as &$user) {
            if($this->users_model->hasInstrument($user['id'])) {
                $instruments = $this->instruments_model->getInstrumentsByUser($user['id']);
                $user['instruments'] = $instruments;
            } else {
                $user['instruments'] = "";
            }
        }
        $data['users'] = $users;

        $data['event']['users'] = $this->events_model->getEventUsers($id);

        //load the view
        $data['main_content'] = 'admin/events/edit';
        $this->load->view('includes/template', $data);

    }

    /**
     * Delete event by his id
     */
    public function delete() {

        $id = $this->uri->segment(4);
        $this->events_model->delete_event($id);
        redirect('admin/events');

    }

}