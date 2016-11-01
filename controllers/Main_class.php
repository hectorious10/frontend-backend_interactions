<?php

class Main_class extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        if($this->session->userdata('user_id')==true){
            redirect('dashboard');
        }
    }

    public function index()
    {
        $this->load->view('portal/inc/header_view');
        $this->load->view('portal/portal_view');
        $this->load->view('portal/inc/footer_view');
    }

    public function login()
    {
        $this->load->model('user_model');
        $login = $this->input->post('username');
        $password = $this->input->post('password');
        $result = $this->user_model->get_user(['ulin' => $login,'upas' => hash('sha256', $password.SALT)], 'users');
        $this->output->set_content_type('application_json');
        if($result){
            $this->session->set_userdata(['ulin' => $result[0]['uid'], 'unm' => $result[0]['unm']]);
            $this->output->set_output(json_encode(['result' => 1]));
            $this->user_model->updatelog($result[0]['uid']);
            return false;
        }
        $this->output->set_output(json_encode(['error' => "Invalid Credentials"]));
    }

    public function create_user(){
        $this->_require_login();
        $this->load->model('user_model');
        $this->output->set_content_type('application_json');
        $this->form_validation->set_rules('login', 'Login', 'required|min_length[4]|max_length[16]|is_unique[users.user_name]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[16]|matches[confirm_password]');
        if($this->form_validation->run() == false){
            $errors = validation_errors();
            $this->output->set_output(json_encode(['result' => 0, 'error' => $errors]));
            return false;
        }
        $entrynumb = $this->user_model->insertuser([
            'user_name' => $this->input->post('login'),
            'user_pass' => hash('sha256', $this->input->post('password').SALT)]);
        if(!$entrynumb){
            $this->output->set_output(json_encode(['result' => 0, 'error' => "something is wrong"]));
        }
        $this->output->set_output(json_encode(['result' => 1]));
        return false;
    }

}