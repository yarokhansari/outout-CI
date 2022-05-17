<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserFriends extends CI_Controller {


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
	public function index( $id )
	{

		/**
		 *  Display all user's friends
		 */
		 
		$join_str[0] = array(
            'table' => 'hoo_friend_request',
            'join_table_id' => 'hoo_friend_request.to_user_id',
            'from_table_id' => 'hoo_users.id',
            'type' => '',
        );

		$columns = 'hoo_users.first_name,hoo_users.last_name,hoo_friend_request.status,hoo_friend_request.is_follow';

		$allUsers = $this->Common->select_data_by_condition('hoo_users',array('hoo_users.is_delete' => '0','hoo_friend_request.from_user_id' => $id),$columns,'','','','',$join_str);
		$data['allUsers'] = $allUsers;

		$data['main_content'] = 'admin/userfriends/index';
        $this->load->view('includes/template', $data);
	}

}
