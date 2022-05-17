<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {


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
		/* Total Users */
		
		$columns = "COUNT(*) as TotalUsers";
		
		$condition = array('is_delete' => '0');
		
		$users = $this->Common->select_data_by_condition("hoo_users", $condition, $columns , '', '', '', '', array());
		
		$data['TotalUsers'] = $users[0]['TotalUsers'];
		
		
		/* Total Business Accounts */
		
		$columns = "COUNT(*) as TotalBusinessUsers";
		
		$condition = array('is_delete' => '0','account_type' => '1');
		
		$users = $this->Common->select_data_by_condition("hoo_users", $condition, $columns , '', '', '', '', array());
		
		$data['TotalBusinessUsers'] = $users[0]['TotalBusinessUsers'];
		
		
		/* Total Premium Accounts */
		
		$columns = "COUNT(*) as TotalPremiumUsers";
		
		$condition = array('is_delete' => '0','account_type' => '2');
		
		$users = $this->Common->select_data_by_condition("hoo_users", $condition, $columns , '', '', '', '', array());
		
		$data['TotalPremiumUsers'] = $users[0]['TotalPremiumUsers'];
		
		
		
		/* Total VIP Members */
		
		$columns = "COUNT(*) as TotalVIPUsers";
		
		$condition = array('is_delete' => '0','is_vip' => '1');
		
		$users = $this->Common->select_data_by_condition("hoo_users", $condition, $columns , '', '', '', '', array());
		
		$data['TotalVIPUsers'] = $users[0]['TotalVIPUsers'];
		
		
		
		$data['main_content'] = 'admin/dashboard/index';
        $this->load->view('includes/template', $data);
	}
}
