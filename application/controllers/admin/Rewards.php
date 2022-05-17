<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rewards extends CI_Controller {


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

		$allrewards = $this->Common->select_data_by_condition('hoo_rewardnew',array(),'*','id','DESC','','',array());
		$data['allrewards'] = $allrewards;
		
		$data['main_content'] = 'admin/rewards/index';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Add package
	 */

	public function add()
	{

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('level', 'Level', 'required');
            $this->form_validation->set_rules('points', 'Points', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect('admin/Rewards/add', 'refresh');
            } else 
            {
               
				$package = array(
                    "name" => $this->input->post('name', TRUE),
                    "level" => $this->input->post('level', TRUE),
                    "points" => $this->input->post('points', TRUE),
					// "created_at" => date('Y-m-d h:i:s'),
                );

			}        

				// $package = array(
                //     "name" => $name,
                //     "price" => $this->input->post('packageamount', TRUE),
                //     "duration" => $this->input->post('packageduration', TRUE),
				// 	"created_at" => date('Y-m-d h:i:s'),
                // );
                $package = $this->Common->insert_data_getid($package, 'hoo_rewardnew');
				if ($package) {
                    $this->session->set_flashdata('success', 'Reward is added successfully.');
                    // redirect($_SERVER['HTTP_REFERER'], 'refresh');
					redirect('admin/rewards/index', 'refresh');
					// return $this->response->redirect(site_url('admin/rewards/index'));
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
			}
		

		$data['main_content'] = 'admin/rewards/add';
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

			$data['info'] = $this->Common->select_data_by_condition('hoo_rewardnew', array('id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/rewards/edit';
        $this->load->view('includes/template', $data);
	}


	/**
	 *  Update package
	 */

	public function update(){

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('level', 'Level', 'required');
            $this->form_validation->set_rules('points', 'points', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				$id = $this->input->post('id', TRUE);
				// $name = $this->input->post('packagename', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_rewardnew", array(), '*', '', '', '', '', array());
				$package = array(
                    "name" => $this->input->post('name', TRUE),
                    "level" => $this->input->post('level', TRUE),
                    "points" => $this->input->post('points', TRUE),
					// "updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($package, 'hoo_rewardnew', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Rewards is Updated successfully.');
                    // redirect($_SERVER['HTTP_REFERER'], 'refresh');
					redirect('admin/rewards/index', 'refresh');
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
        }
		else{

			$delete_package = array(
				"is_delete" => '1',
			);
			if ($this->Common->delete_data('hoo_rewardnew', 'id', $id)) {
				$this->session->set_flashdata('success', 'Data is deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}

	}
}
