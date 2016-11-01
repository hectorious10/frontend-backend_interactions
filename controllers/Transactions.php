<?php
class Backoffice extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->_require_login();
    }

    private function _require_login(){
        if($this->session->userdata('ulin')==false){
            $this->logout();
            $this->output->set_output(json_encode(['result' => 0, 'error' => 'You are not authorized']));
            return false;
        }
    }


    public function index(){
        $this->load->view('portal/backoffice/inc/header_view');
        $this->load->view('portal/backoffice/admin_view');
        $this->load->view('portal/backoffice/inc/footer_view');
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect('/portal');
    }

    public function articles($id = null, $loc = null){
        $this->_require_login();
        $this->load->model('user_model');
        $this->output->set_content_type('application_json');
        if($id != null || $loc != null){
            if($loc != null){
                $query = $this->user_model->get_art($id, $loc);
            }else{
                $query = $this->user_model->get_art($id);}
        }else{
            $query = $this->user_model->get_allart();
        }
        $this->output->set_output(json_encode($query));
    }

    public function entries($id = null){
        $this->_require_login();
        $this->load->model('user_model');
        $this->output->set_content_type('application_json');
        $query = $this->user_model->get_list($id, 'cl_info');
        $this->output->set_output(json_encode($query));
    }

    public function alldata($id = null, $loc = null){
        $this->_require_login();
        $this->load->model('user_model');
        $this->output->set_content_type('application_json');
        $query = $this->user_model->get_alldata($id, $loc);
        $this->output->set_output(json_encode($query));
    }

    public function viewinv($custnf){
        //$this->_require_login();
        $this->load->model('user_model');
        $info = $this->user_model->get_alldata($custnf);
        foreach($info[0] as $_key => $_value){
            $data[$_key] = $_value;
        }
        $this->load->view('portal/backoffice/invoice_view', $data);
    }

    public function editinv($custnf){
        $this->_require_login();
        $this->load->model('user_model');
        $info = $this->user_model->get_alldata($custnf);
        foreach($info[0] as $_key => $_value){
            $data[$_key] = $_value;
        }
        $this->load->view('portal/backoffice/inc/header_view');
        $this->load->view('portal/backoffice/editinv_view', $data);
        $this->load->view('portal/backoffice/inc/footer_view');
    }

    public function updateinv(){
        $this->_require_login();
        $this->output->set_content_type('application_json');
        $this->load->model('user_model');
        $cld = $this->input->post('cldid');
        $cldid = $this->input->post('cid');
        $userdat = array('clddt'=>$this->input->post('dt_qt'), 'cldstrpu' => $this->input->post('str_pu'), 'cldctpu' => $this->input->post('ct_pu'), 'cldstpu' => $this->input->post('st_pu'), 'cldzcpu' => $this->input->post('zip_pu'),
            'cldflpu' => $this->input->post('fl_pu'), 'cldelpu' => $this->input->post('el_pu'), 'cldstrdl' => $this->input->post('str_dl'), 'cldctdl' => $this->input->post('ct_dl'), 'cldstdl' => $this->input->post('st_dl'), 'cldzcdl' => $this->input->post('zip_dl'), 'cldfldl' => $this->input->post('fl_dl'), 'cldeldl' => $this->input->post('el_dl'),
            'cldstrapu' => $this->input->post('str_apu'), 'cldctapu' => $this->input->post('ct_apu'), 'cldstapu' => $this->input->post('st_apu'), 'cldzcapu' => $this->input->post('zip_apu'), 'cldflapu' => $this->input->post('fl_apu'), 'cldelapu' => $this->input->post('el_apu'), 'cldstradl' => $this->input->post('str_adl'), 'cldctadl' => $this->input->post('ct_adl'), 'cldstadl' => $this->input->post('st_adl'), 'cldzcadl' => $this->input->post('zip_adl'), 'cldfladl' => $this->input->post('fl_adl'), 'cldeladl' => $this->input->post('el_adl'),
            'cldpa' => $this->input->post('pac'),'hr_qt' => $this->input->post('hr_qt'),'cldppa' => $this->input->post('ppac'),'cldpcl' => $this->input->post('pclea'), 'cldre' => $this->input->post('reas'), 'cldcl' => $this->input->post('clea'), 'cldpcl' => $this->input->post('sh_term'),'cldmsg' => $this->input->post('cldmsg'));
        $userinf = array('cfnm' => $this->input->post('fn_qt'), 'clnm' => $this->input->post('ln_qt'), 'ceml' => $this->input->post('eml_qt'), 'cpnb' => $this->input->post('pn_qt'), 'clpqt' => $this->input->post('clp_qt') );
        $xtraitm = array('frslr' => $this->input->post('frslr'),'frstat' => $this->input->post('frstat'),'frdrv' => $this->input->post('frdrv'),'frhr' => $this->input->post('frhr'), 'frstm' => $this->input->post('frstm'),'frftm' => $this->input->post('frftm'), 'frmsg' => $this->input->post('frmsg'), 'frsubt' => $this->input->post('frsubt'), 'frbal' => $this->input->post('frbal'), 'frtt'=>$this->input->post('frtt'),'frth'=>$this->input->post('frth'),'frfr'=>$this->input->post('frfr'),'frps'=>$this->input->post('frps'),'frstor'=>$this->input->post('frstor'),'frpas'=>$this->input->post('frpas'),'frus'=>$this->input->post('frus'),'frads'=>$this->input->post('frads'),'frinsdsc'=>$this->input->post('frinsdsc'),'froth'=>$this->input->post('froth'),'frdep'=>$this->input->post('froth'),'frdep'=>$this->input->post('frdep'),'frtot'=>$this->input->post('frtot'),'frtotw'=>$this->input->post('frtotw'));

        $usrnfo = $this->user_model->updatenfo($cldid, $userinf);
        $usrdat = $this->user_model->updateinvxt($cld, $userdat);
        $usrxtr = $this->user_model->updateinvxt($cld, $xtraitm);
        $target['arts'] = $this->input->post('arts');
        $target2['artsct'] =$this->input->post('artsct');
        foreach ($target['arts'] as $key => $art) {
            $resart = $this->user_model->updateinv($cld, $target2['artsct'][$key], $art);
        }
        if($resart || $usrxtr || $usrnfo || $usrdat){$this->output->set_output(json_encode(['result' => 1, 'msg'=>'Your Request has been received']));}
        else{$this->output->set_output(json_encode(['error' => "Invalid Data"]));}
    }

    public function removequo($id){
        $this->_require_login();
        $this->load->model('user_model');
        $this->output->set_content_type('application_json');
        $result = $this->user_model->delete($id);
        if($result){
            $this->load->view('portal/backoffice/inc/header_view');
            $this->load->view('portal/backoffice/admin_view');
            $this->load->view('portal/backoffice/inc/footer_view');
        }
    }

}