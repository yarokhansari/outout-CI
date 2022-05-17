<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserEvents extends CI_Controller {


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
		 *  Display all events
		 */

		$allEvents = $this->Common->select_data_by_condition('hoo_events',array('is_delete' => '0','userid' => $id),'*','id','DESC','','',array());
		$data['allEvents'] = $allEvents;

		$data['main_content'] = 'admin/userevent/index';
        $this->load->view('includes/template', $data);
	}

	public function view( $id ){
		
		/* View Event Details */
		
		$join_str[0] = array(
            'table' => 'hoo_currency',
            'join_table_id' => 'hoo_currency.id',
            'from_table_id' => 'hoo_events.currency_id',
            'type' => '',
        );
	
		$columns = 'hoo_events.event_name,hoo_events.event_date,hoo_events.event_city,hoo_currency.symbol,hoo_events.price,hoo_events.event_type,hoo_events.additional_info,hoo_events.event_invitees';
		
		$eventDetails = $this->Common->select_data_by_condition('hoo_events',array('hoo_events.is_delete' => '0','hoo_events.id' => $id),$columns,'','','','',$join_str);
		
		$invites = $eventDetails[0]['event_invitees'];
		
		$condition = "id IN (".$invites.") AND is_delete = '0'";
		
		$userdetails = $this->Common->select_data_by_condition('hoo_users', $condition , 'id,first_name,last_name' ,'','','','',array());
		
		
		$data['eventDetails'] = $eventDetails;
		$data['userdetails'] = $userdetails;

		$data['main_content'] = 'admin/userevent/view';
        $this->load->view('includes/template', $data);
		
	}
	

}
