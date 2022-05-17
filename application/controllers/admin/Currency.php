<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency extends CI_Controller {


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
		 *  Display all currency
		 */

		$allCurrency = $this->Common->select_data_by_condition('hoo_currency',array('is_delete' => '0'),'*','id','DESC','','',array());
		$data['allCurrency'] = $allCurrency;
		
		$data['main_content'] = 'admin/currency/index';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Add currency
	 */

	public function add()
	{

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Currency name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect('admin/Currency/add', 'refresh');
            } else 
            {
				$name = $this->input->post('name', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_currency", array(), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name) {
                        $this->session->set_flashdata('error', 'Currency name already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }
               
				$addcurrency = array(
                    "name" => $name,
                    "code" => $this->input->post('code', TRUE),
					"symbol" => $this->input->post('symbol', TRUE),
					"created_at" => date('Y-m-d h:i:s'),
                );
                $currency = $this->Common->insert_data_getid($addcurrency, 'hoo_currency');
				if ($currency) {
                    $this->session->set_flashdata('success', 'Currency is added successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
			}
		}

		$data['main_content'] = 'admin/currency/add';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Edit currency with id
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

			$data['info'] = $this->Common->select_data_by_condition('hoo_currency', array('is_delete' => '0' , 'id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/currency/edit';
        $this->load->view('includes/template', $data);
	}


	/**
	 *  Update currency
	 */

	public function update(){

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('name', 'Currency name', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				$id = $this->input->post('id', TRUE);
				$name = $this->input->post('name', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_currency", array(), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name && $info_['id'] != $id) {
                        $this->session->set_flashdata('error', 'Currency name already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }

				$updateCurrency = array(
					"name" => $name,
                    "code" => $this->input->post('code', TRUE),
					"symbol" => $this->input->post('symbol', TRUE),
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($updateCurrency, 'hoo_currency', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Currency is Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }


			}
		}

	}

	/**
	 * Delete Currency based on id
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

			$delete_currency = array(
				"is_delete" => '1',
			);
			if ($this->Common->update_data($delete_currency, 'hoo_currency', 'id', $id)) {
				$this->session->set_flashdata('success', 'Currency is deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}

	}
}
