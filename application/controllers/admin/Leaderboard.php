<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaderboard extends CI_Controller {


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
		$join_str[0] = array(
			'table' => 'hoo_users',
			'join_table_id' => 'hoo_users.id',
			'from_table_id' => 'hoo_gift_notification.user_id',
			'type' => '',
		);
		
		$columns = array('hoo_gift_notification.id','hoo_users.username','hoo_users.email', 'hoo_gift_notification.status');
		
		$leaderboardinfo = $this->Common->select_data_by_condition('hoo_gift_notification', array() , $columns , '', '', '', '', $join_str);
		$data['leaderboardinfo'] = $leaderboardinfo;
		
		$data['main_content'] = 'admin/leaderboard/index';
        $this->load->view('includes/template', $data);
	}
	
	
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

			$data['info'] = $this->Common->select_data_by_condition('hoo_gift_notification', array('id' => $id), '*', '', '', '', '', array());

		}

		$data['main_content'] = 'admin/leaderboard/edit';
        $this->load->view('includes/template', $data);
	}


	public function update(){
		
		if ($this->input->method() == 'post') {
            
			$id = $this->input->post('id', TRUE);
			$status = $this->input->post('emailstatus', TRUE);
		
			$leaderboarddata = array(
				"status" => $status,
				"updated_at" => date('Y-m-d h:i:s'),
			);

			if ($this->Common->update_data($leaderboarddata, 'hoo_gift_notification', 'id', $id)) {
				$this->session->set_flashdata('success', 'Email Status is updated successfully.');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			} else {
				$this->session->set_flashdata('error', 'ERROR OCCURED. TRY AGAIN LATER!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}

		}
		
	}

}
