<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends CI_Controller {


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
		 *  Display all packages
		 */

		$allPackages = $this->Common->select_data_by_condition('hoo_packages',array('is_delete' => '0'),'*','id','DESC','','',array());
		$data['allPackages'] = $allPackages;
		
		$data['main_content'] = 'admin/package/index';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Add package
	 */

	public function add()
	{

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('packagename', 'Package name', 'required');
            $this->form_validation->set_rules('packageamount', 'Package Amount', 'required');
            $this->form_validation->set_rules('packageduration', 'Package Duration', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect('admin/Package/add', 'refresh');
            } else 
            {
               
				$name = $this->input->post('packagename', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_packages", array(), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name) {
                        $this->session->set_flashdata('error', 'Package name already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }

				$package = array(
                    "name" => $name,
                    "price" => $this->input->post('packageamount', TRUE),
                    "duration" => $this->input->post('packageduration', TRUE),
					"created_at" => date('Y-m-d h:i:s'),
                );
                $package = $this->Common->insert_data_getid($package, 'hoo_packages');
				if ($package) {
                    $this->session->set_flashdata('success', 'Package is added successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
			}
		}

		$data['main_content'] = 'admin/package/add';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Edit package with id
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

			$data['info'] = $this->Common->select_data_by_condition('hoo_packages', array('is_delete' => '0' , 'id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/package/edit';
        $this->load->view('includes/template', $data);
	}


	/**
	 *  Update package
	 */

	public function update(){

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('packagename', 'Package name', 'required');
            $this->form_validation->set_rules('packageamount', 'Package Amount', 'required');
            $this->form_validation->set_rules('packageduration', 'Package Duration', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				$id = $this->input->post('id', TRUE);
				$name = $this->input->post('packagename', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_packages", array(), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name && $info_['id'] != $id) {
                        $this->session->set_flashdata('error', 'Package name already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }

				$package = array(
                    "name" => $name,
                    "price" => $this->input->post('packageamount', TRUE),
                    "duration" => $this->input->post('packageduration', TRUE),
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($package, 'hoo_packages', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Package is Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }

			}
		}

	}

	/**
	 * Delete Package based on id
	 */

	public function delete($id){

		if ($id == '') {
           
			echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					<strong>Error!</strong> No record found.
				  </div>';
            die();
        }else{

			$delete_package = array(
				"is_delete" => '1',
			);
			if ($this->Common->update_data($delete_package, 'hoo_packages', 'id', $id)) {
				$this->session->set_flashdata('success', 'Package is deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}

	}
}
