<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {


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
		 *  Display all settings
		 */

        $condition = array(); 

		$allSettings = $this->Common->select_data_by_condition('hoo_settings', $condition ,'*','id','ASC','','',array());
		$data['allSettings'] = $allSettings;
		
		$data['main_content'] = 'admin/setting/index';
        $this->load->view('includes/template', $data);
	}

	
	/**
	 *  Edit setting with id
	 */

	public function edit($id)
	{

		if ($id == '') {
           
			echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					<strong>Error!</strong> No record found.
				  </div>';
            die();
        }else{

			$data['info'] = $this->Common->select_data_by_condition('hoo_settings', array( 'id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/setting/edit';
        $this->load->view('includes/template', $data);
	}


	/**
	 *  Update package
	 */

	public function update(){

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('value', 'Setting Value', 'required');
           
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				$id = $this->input->post('id', TRUE);
				$setting = array(
                    "value" => $this->input->post('value', TRUE),
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($setting, 'hoo_settings', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Setting is Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }


			}
		}

	}

}
