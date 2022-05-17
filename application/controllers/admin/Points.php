<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Points extends CI_Controller {


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
		 *  Display all points
		 */

		$allPoints = $this->Common->select_data_by_condition('hoo_points',array('is_delete' => '0'),'*','id','DESC','','',array());
		$data['allPoints'] = $allPoints;
		
		$data['main_content'] = 'admin/points/index';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Add points
	 */

	public function add()
	{

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('points', 'Points', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect('admin/Points/add', 'refresh');
            } else {
               
			    $description = $this->input->post('description', TRUE);
				$points = $this->input->post('points', TRUE);
				$pointdetails = array(
                    "description" => $description,
                    "points" => $points,
					"created_at" => date('Y-m-d h:i:s'),
                );
                $pointinfo = $this->Common->insert_data_getid($pointdetails, 'hoo_points');
				if ($pointinfo) {
                    $this->session->set_flashdata('success', 'Points are added successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
			}
		}

		$data['main_content'] = 'admin/points/add';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Edit points with id
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

			$data['info'] = $this->Common->select_data_by_condition('hoo_points', array('is_delete' => '0' , 'id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/points/edit';
        $this->load->view('includes/template', $data);
	}


	/**
	 *  Update points
	 */

	public function update(){

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('points', 'Points', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				$id = $this->input->post('id', TRUE);
				$points = $this->input->post('points', TRUE);
				

				$pointdetails = array(
                    "points" => $points,
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($pointdetails, 'hoo_points', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Points are Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }

			}
		}

	}

	/**
	 * Delete Point based on id
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

			$delete_point = array(
				"is_delete" => '1',
			);
			if ($this->Common->update_data($delete_point, 'hoo_points', 'id', $id)) {
				$this->session->set_flashdata('success', 'Points are deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}

	}
}
