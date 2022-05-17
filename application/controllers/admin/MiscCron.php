<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class MiscCron extends CI_Controller {
	
	public function __construct() {
        parent::__construct();

        $this->load->model('Common');
		$this->load->helper(array('form', 'url'));
        
    }
	
	public function UpdateTrial(){
		
		$today = date('Y-m-d');
		
		$columns = "id,first_name,last_name,DATEDIFF('".$today."', created_at) as days";
		
		$condition = array('is_delete' => '0','account_type' => '1','is_trial_completed' => '0');
		
		$users = $this->Common->select_data_by_condition('hoo_users', $condition, $columns , '', '', '', '', array());
		
		foreach( $users as $user  ){
			
			if($user['days'] == 30){
				
				$updateTrialPeriod = array(
                    "is_trial_completed" => '1',
					"updated_at" => date('Y-m-d h:i:s'),
                );

			    $this->Common->update_data($updateTrialPeriod, 'hoo_users', 'id', $user['id']);
				
			}
		}
		
		
		
		
	}
	
}