<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePassword extends CI_Controller {


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
	public function index(){

		$adminDetails = $this->Common->select_data_by_condition('hoo_admin', array( 'id' => 1), '*', '', '', '', '', array());
	    $data['admin_id'] = $adminDetails[0]['id'];


		$data['main_content'] = 'admin/change';
        $this->load->view('includes/template', $data);
	
	}

	/** Update Password based on admin  */
	
	public function update(){

		if ($this->input->method() == 'post') {
		
			$id = $this->input->post('admin_id', TRUE);
			$adminData = array(
				"password" => password_hash($this->input->post('newpassword'), PASSWORD_BCRYPT),
				"updated_at" => date('Y-m-d h:i:s'),
			);
			
			if ($this->Common->update_data($adminData, 'hoo_admin', 'id', $id)) {
				$this->session->set_flashdata('success', 'New Password is Updated successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
			
	}


	}


}
