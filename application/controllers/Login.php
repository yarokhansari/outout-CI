<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


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
	public function index(){

		$this->load->view('admin/index');
	
	}

	public function authenticate() {

        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata('login_error', 'Please follow validation rules!');
            redirect('login', 'refresh');
        
		} else {

           	$email = $this->input->post('email');
        	$password = $this->input->post('password');
            /* Check User Availabilty */
            $checkAuth = $this->Common->selectRecordByFields('hoo_admin', "email LIKE '$email'");
            if (!empty($checkAuth)) {   
                
                $dbPassword = $checkAuth['password'];
                $dbuseremail = $checkAuth['email'];
                
                if (($email == $dbuseremail) && password_verify($password, $dbPassword)) {
					//Set login logs
					$new_session_id = $checkAuth['id'].'-'.$this->session->session_id;
					$this->session->set_userdata('session_id', $new_session_id);
                    redirect('admin/Dashboard', 'refresh');
                } else {
              
                    $this->session->set_flashdata('login_error', 'Invalid username or password.');
                    redirect('login', 'refresh');
                }
            } else {
                $this->session->set_flashdata('login_error', 'Invalid username or password.');
                redirect('login', 'refresh');
            }
        }
    }

	public function forgetpassword(){

		$this->load->view('admin/forget');

	}

	public function forget(){

		
		$this->form_validation->set_rules('email_address', 'Email', 'required');
        
        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata('login_error', 'Please follow validation rules!');
            redirect('login', 'refresh');
        
		} else {

			$forgot_email_address = $this->input->post('email_address');
            $getUser = $this->Common->selectRecordByFields('hoo_admin',"email LIKE '".$forgot_email_address."'");
			$admin_id = $getUser['id'];
			$admin_email = $getUser['email'];

			if( !empty($getUser) ){

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$headers .= 'From: <info@cricketcups.com>' . "\r\n";

				$newpwd = rand().date('is');
				$new_password = password_hash($newpwd, PASSWORD_BCRYPT);

				$adminData = array(
					"password" => $new_password,
					"updated_at" => date('Y-m-d h:i:s'),
				);
				$base_url = base_url();
				if ($this->Common->update_data($adminData, 'hoo_admin', 'id', $admin_id)) {

					//$logo=$this->config->item('common_assets_path').'images/logo/logo.jpg';
					/* Forgot Password mail Send Start */
					$body ="<div style='text-align:center'>Your new password set is: <b>".$newpwd."</b></div>";
					$emailBody = file_get_contents(base_url() . 'email-templates/forget.html');
					// $emailBody = str_replace('<<LOGO>>',$logo , $emailBody);
					$emailBody = str_replace('<<EMAIL_CONTENT>>', $body, $emailBody); 
					$emailBody = str_replace('<<BASEURL>>', $base_url, $emailBody);
					$subject = "Password Updated";
					$to_user =  $admin_email;
					
					mail($to_user,$subject,$emailBody,$headers);

					$this->session->set_flashdata('success', 'Mail send. Please check your email inbox for new password');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
				else
				{
					$this->session->set_flashdata('forgot_password_error', 'Password is not yet updated');
					redirect('login', 'refresh');
				}

			}else{

				$this->session->set_flashdata('error', 'Please check your email address');
				redirect('login', 'refresh');

			}

			

		}

	}

	

	public function login_history(){

		$data['main_content'] = 'admin/login/history';
        $this->load->view('includes/template', $data);

	}

	public function logout(){


		$this->session->sess_destroy();
		redirect('Login', 'refresh');

	}
}
