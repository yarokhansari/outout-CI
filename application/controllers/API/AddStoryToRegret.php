<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AddStoryToRegret extends REST_Controller {
	
	function index(){
		
		$alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	    if( !empty($alldevices) ){
			
			$userid = $alldevices[0]['usrer_id'];
			
			
			
			
		}
		
	}
	
}