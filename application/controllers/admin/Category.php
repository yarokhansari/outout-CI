<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {


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
		 *  Display all category
		 */

		$allCategory = $this->Common->select_data_by_condition('hoo_category',array('is_delete' => '0'),'*','id','DESC','','',array());
		$data['allCategory'] = $allCategory;
		
		$data['main_content'] = 'admin/category/index';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Add category
	 */

	public function add()
	{

		if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('categoryname', 'Category name', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect('admin/Category/add', 'refresh');
            } else 
            {
				$name = $this->input->post('categoryname', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_category", array(), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name) {
                        $this->session->set_flashdata('error', 'Category name already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }
               
				$addcategory = array(
                    "name" => $name,
                    "status" => $this->input->post('status', TRUE),
					"created_at" => date('Y-m-d h:i:s'),
                );
                $category = $this->Common->insert_data_getid($addcategory, 'hoo_category');
				if ($category) {
                    $this->session->set_flashdata('success', 'Category is added successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }
			}
		}

		$data['main_content'] = 'admin/category/add';
        $this->load->view('includes/template', $data);
	}

	/**
	 *  Edit category with id
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

			$data['info'] = $this->Common->select_data_by_condition('hoo_category', array('is_delete' => '0' , 'id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/category/edit';
        $this->load->view('includes/template', $data);
	}


	/**
	 *  Update category
	 */

	public function update(){

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('categoryname', 'Category name', 'required');
			if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }else{
				$id = $this->input->post('id', TRUE);
				$name = $this->input->post('categoryname', TRUE);
				$info = $this->Common->select_data_by_condition("hoo_category", array(), '*', '', '', '', '', array());
                foreach ($info as $key => $info_) {
                    if($info_['name'] == $name && $info_['id'] != $id) {
                        $this->session->set_flashdata('error', 'Category name already exists in database.');
                        redirect($_SERVER['HTTP_REFERER'], 'refresh');
                    }
                }

				$updateCategory = array(
					"name" => $name,
                    "status" => $this->input->post('status', TRUE),
					"updated_at" => date('Y-m-d h:i:s'),
                );

				if ($this->Common->update_data($updateCategory, 'hoo_category', 'id', $id)) {
                    $this->session->set_flashdata('success', 'Category is Updated successfully.');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }


			}
		}

	}

	/**
	 * Delete Category based on id
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

			$delete_category = array(
				"is_delete" => '1',
			);
			if ($this->Common->update_data($delete_category, 'hoo_category', 'id', $id)) {
				$this->session->set_flashdata('success', 'Category is deleted successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}

	}
}
