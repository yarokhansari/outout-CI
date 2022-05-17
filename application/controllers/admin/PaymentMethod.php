<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentMethod extends CI_Controller {


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

	/**
	 *  Display all payment method
	 */

	public function index()
	{

		$allPaymentMethods = $this->Common->select_data_by_condition('hoo_payment_method',array('is_delete' => '0'),'*','id','DESC','','',array());
		$data['allPaymentMethods'] = $allPaymentMethods;

		$data['main_content'] = 'admin/payment_method/index';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Add payment method
	 */

	public function add()
	{

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Payment Method Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect('admin/PaymentMethod/add', 'refresh');
            } else 
            {
                
				$name = $this->input->post('name', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_payment_method", array(), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name) {
                        $this->session->set_flashdata('error', 'Payment Method already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }

				$paymentmethod = array(
                    "name" => $name,
					"created_at" => date('Y-m-d h:i:s'),
                );
                $paymentmethod = $this->Common->insert_data_getid($paymentmethod, 'hoo_payment_method');
				if ($paymentmethod) {
                    $this->session->set_flashdata('success', 'Payment Method is added successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
			}
		}

		$data['main_content'] = 'admin/payment_method/add';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Edit payment method
	 */

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

			$data['info'] = $this->Common->select_data_by_condition('hoo_payment_method', array('is_delete' => '0' , 'id' => $id), '*', '', '', '', '', array());

		}


		$data['main_content'] = 'admin/payment_method/edit';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Update payment method
	 */

	public function update(){

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('name', 'Payment Method Name', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{

				$id = $this->input->post('id', TRUE);
				$name = $this->input->post('name', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_payment_method", array(), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name && $info_['id'] != $id) {
                        $this->session->set_flashdata('error', 'Payment Method already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }
				$paymentmethod = array(
                    "name" => $name,
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($paymentmethod, 'hoo_payment_method', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Payment Method is Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }


			}
		}

	}

	/**
	 * Delete Payment Method based on id
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

			$delete_payment_method = array(
				"is_delete" => '1',
			);
			if ($this->Common->update_data($delete_payment_method, 'hoo_payment_method', 'id', $id)) {
				$this->session->set_flashdata('success', 'Payment Method is deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}

	}
}
