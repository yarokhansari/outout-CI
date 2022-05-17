<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {


	public function __construct() {
        parent::__construct();

        $this->load->model('Common');
        $this->load->helper(array('form', 'url'));
      
    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->load->view('front/index');
	}
	
	public function customer(){
	    
	    $this->load->view('front/customer');
	    
	}
	
	public function business(){
	    
	    $this->load->view('front/business');
	    
	}
	
	public function company(){
	    
	    $this->load->view('front/company');
	    
	}

	public function contact(){
	    
	    $this->load->view('front/contact');
	    
	}
	
	public function terms(){
	    
	    $this->load->view('front/termsandcondition');
	    
	}
	
	public function support(){
	    
	    $this->load->view('front/support');
	    
	}
	
	public function privacy(){
		
		$this->load->view('front/privacy');
	}
	
	public function confirmuser(){

		$user_id = base64_decode($_GET['TGhyfdgd5863dgggtr']);
        if($user_id !='' && $user_id !='0'){
            $checkUser = $this->Common->selectRecordByFields('hoo_users','id='.$user_id.' AND is_verified = "0"');
            if (!empty($checkUser)){   
                $update_array=array(
                    'is_verified'=>'1',
					'updated_at' => date('Y-m-d h:i:s'),
                );
        
                if($this->Common->update_data($update_array, 'hoo_users', 'id', $checkUser['id'])){
                    $this->session->set_flashdata('success', 'Account verified successfully');
                    redirect('/', 'refresh');
                }
                else{
                    $this->session->set_flashdata('error', 'Account can not verified');
                    redirect('/', 'refresh');
                }
            }
            else{
                $this->session->set_flashdata('error', 'Account already verified');
                redirect('/', 'refresh');
            }

        }
       redirect('/','refresh');
	}
}
