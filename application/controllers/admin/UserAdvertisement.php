<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserAdvertisement extends CI_Controller {


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
		 *  Display all advertisement
		 */

		$allAdvertisement = $this->Common->select_data_by_condition('hoo_advertisment',array('is_delete' => '0','userid' => $id),'*','id','DESC','','',array());
		$data['allAdvertisement'] = $allAdvertisement;
		
		$data['main_content'] = 'admin/useradvertisement/index';
        $this->load->view('includes/template', $data);
	}

	
}
