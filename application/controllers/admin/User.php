<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {


	public function __construct() {
        parent::__construct();

		$this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');

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

		/**
		 *  Display all users
		 */
		 
		$condition = '';

		$allUsers = $this->Common->select_data_by_condition('hoo_users',array('is_delete' => '0'),'*','id','DESC','','',array());
		$data['allUsers'] = $allUsers;

		$data['main_content'] = 'admin/user/index';
        $this->load->view('includes/template', $data);
	}

	public function add()
	{
		
		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('firstname', 'First name', 'required');
			$this->form_validation->set_rules('lastname', 'Last name', 'required');
			$this->form_validation->set_rules('dob', 'Date of Birth', 'required');
			$this->form_validation->set_rules('gender', 'Gender', 'required');
			$this->form_validation->set_rules('phonenumber', 'Phone no', 'required');
			$this->form_validation->set_rules('countrycode', 'Country Code', 'required');
			$this->form_validation->set_rules('emailaddress', 'Email Address', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('account_type', 'Account Type', 'required');
			$this->form_validation->set_rules('businesscategory', 'Business Category', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				
				$info = $this->model_name->select_data_by_condition("hoo_users", array(), '*', '', '', '', '', array());
				foreach ($info as $key => $info_) {
					if($info_['email'] == $data['email']) {
						$emailExists = 1;
					}
					if($info_['phone_number'] == $data['phone_number']) {
						$phoneExists = 1;
					}
					if($info_['username'] == $data['username']) {
						$usernameExists = 1;
					}
				}
				
				if( $emailExists == 1 ){
					$this->session->set_flashdata('error', 'This email already exists. Please register using another email address');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}else if( $phoneExists == 1 ){
					$this->session->set_flashdata('error', 'This phone number already exists. Please register using another phone number');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
			    }else if( $usernameExists == 1 ){
					$this->session->set_flashdata('error', 'This username already exists. Please register using another username');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}else{
				
					$id = $this->input->post('id', TRUE);
					$first_name = $this->input->post('firstname', TRUE);
					$last_name = $this->input->post('lastname', TRUE);
					$dob = $this->input->post('dob', TRUE);
					$gender = $this->input->post('gender', TRUE);
					$phone_number = $this->input->post('phonenumber', TRUE);
					$country_code = $this->input->post('countrycode', TRUE);
					$emailaddress = $this->input->post('emailaddress', TRUE);
					$username = $this->input->post('username', TRUE);
					$password = $this->input->post('password', TRUE);
					$account_type = $this->input->post('account_type', TRUE);
					$businesscategory = $this->input->post('businesscategory', TRUE);
					
					$addUser = array(
						"first_name" => $first_name,
						"last_name" => $last_name,
						"dob" => $dob,
						"gender" => $gender,
						"phone_number" => $phone_number,
						"country_code" => $country_code,
						"email" => $country_code,
						"username" => $username,
						"password" => $password,
						"account_type" => $account_type,
						"catid" => $businesscategory,
						"created_at" => date('Y-m-d h:i:s'),
					);

					$userid = $this->Common->insert_data_getid($addUser, 'hoo_users');
					if ($userid) {
						
						/* Send Mail on registered user email address */
						

						$url = base_url();
						$emailBody = file_get_contents(base_url() .'email-templates/admin-adduser.html');
						$emailBody = str_replace('<<BASEURL>>', $url, $emailBody); // Dynamic variable
						$emailBody = str_replace('<<USERNAME>>', $fullName, $emailBody); // Dynamic variable
						$emailBody = str_replace('<<PASSWORD>>', $password, $emailBody); // Dynamic variable
						$subject = "OutOut Registration";
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= 'From: <info@outout.app>' . "\r\n";

						$sendmail  = mail($emailaddress,$subject,$emailBody,$headers);
						
						$sendmail = 1;
						
						if( $sendmail == 1 ){
							$this->session->set_flashdata('success', 'Users are added successfully.');
							redirect($_SERVER['HTTP_REFERER'], 'refresh');
						}else {
							$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
							redirect($_SERVER['HTTP_REFERER'], 'refresh');
						}
						
						
					} else {
						$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
				}

			}
		}
		
		$data['allCategory'] = $this->Common->select_data_by_condition('hoo_category',array('is_delete' => '0'),'*','id','DESC','','',array());
		
		$data['main_content'] = 'admin/user/add';
        $this->load->view('includes/template', $data);
	}

	public function edit( $id )
	{
		
		if ($id == '') {
           
			echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					<strong>Error!</strong> No record found.
				  </div>';
            die();
        }else{

			$data['info'] = $this->Common->select_data_by_condition('hoo_users', array('is_delete' => '0' , 'id' => $id), '*', '', '', '', '', array());
			$data['countries'] = $this->Common->select_data_by_condition('hoo_country', array('is_delete' => '0'), '*', '', '', '', '', array());
		}
		
		$data['main_content'] = 'admin/user/edit';
        $this->load->view('includes/template', $data);
	}
	
	public function update(){
		
		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('firstname', 'First name', 'required');
			$this->form_validation->set_rules('lastname', 'Last name', 'required');
			$this->form_validation->set_rules('dob', 'Date of Birth', 'required');
			$this->form_validation->set_rules('gender', 'Gender', 'required');
			$this->form_validation->set_rules('phonenumber', 'Phone no', 'required');
			$this->form_validation->set_rules('countrycode', 'Country Code', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				$id = $this->input->post('id', TRUE);
				$first_name = $this->input->post('firstname', TRUE);
				$last_name = $this->input->post('lastname', TRUE);
				$dob = $this->input->post('dob', TRUE);
				$gender = $this->input->post('gender', TRUE);
				$phone_number = $this->input->post('phonenumber', TRUE);
				$country_code = $this->input->post('countrycode', TRUE);
				

				$updateUser = array(
					"first_name" => $first_name,
                    "last_name" => $last_name,
					"dob" => $dob,
					"gender" => $gender,
					"phone_number" => $phone_number,
					"country_code" => $country_code,
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($updateUser, 'hoo_users', 'id', $id)) {
                    $this->session->set_flashdata('success', 'User is Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }


			}
		}
		
		
	}

	public function delete( $id )
	{

		if ($id == '') {
           
			echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					<strong>Error!</strong> No record found.
				  </div>';
            die();
        }else{

			$delete_user = array(
				"is_delete" => '1',
			);
			if ($this->Common->update_data($delete_user, 'hoo_users', 'id', $id)) {
				
				/* Delete it's connected table data as well */
				
				$this->session->set_flashdata('success', 'User is deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}
		
	}

	public function view( $id )
	{

		$getUser = $this->Common->select_data_by_condition('hoo_users',array('is_delete' => '0' , 'id' => $id),'*','','','','',array());
		$data['getUser'] = $getUser;

		$data['main_content'] = 'admin/user/view';
        $this->load->view('includes/template', $data);
	}

	


}
