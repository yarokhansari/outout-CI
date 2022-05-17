<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends CI_Controller {


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
		 *  Display all country
		 */

		$allCountry = $this->Common->select_data_by_condition('hoo_country',array('is_delete' => '0'),'*','id','DESC','','',array());
		$data['allCountry'] = $allCountry;
		
		$data['main_content'] = 'admin/country/index';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Add country
	 */

	public function add()
	{

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Country name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect('admin/Country/add', 'refresh');
            } else 
            {
				$name = $this->input->post('name', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_country", array('is_delete' => '0'), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name) {
                        $this->session->set_flashdata('error', 'Country name already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }
               
				$addcountry = array(
                    "name" => $name,
					"created_at" => date('Y-m-d h:i:s'),
                );
                $country = $this->Common->insert_data_getid($addcountry, 'hoo_country');
				if ($country) {
                    $this->session->set_flashdata('success', 'Country is added successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
			}
		}

		$data['main_content'] = 'admin/country/add';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Edit country with id
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

			$data['info'] = $this->Common->select_data_by_condition('hoo_country', array('is_delete' => '0' , 'id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/country/edit';
        $this->load->view('includes/template', $data);
	}


	/**
	 *  Update country
	 */

	public function update(){

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('name', 'Country name', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				$id = $this->input->post('id', TRUE);
				$name = $this->input->post('name', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_country", array('is_delete' => '0'), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name && $info_['id'] != $id) {
                        $this->session->set_flashdata('error', 'Country name already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }

				$updateCountry = array(
					"name" => $name,
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($updateCountry, 'hoo_country', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Country is Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }


			}
		}

	}

	/**
	 * Delete Country based on id
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

			$delete_country = array(
				"is_delete" => '1',
			);
			if ($this->Common->update_data($delete_country, 'hoo_country', 'id', $id)) {
				$this->session->set_flashdata('success', 'Country is deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}

	}
}
