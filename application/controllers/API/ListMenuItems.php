<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class ListMenuItems extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
			    
		$condition = array('is_delete' => '0','userid' => $userid);
		
		$menuinfo = $this->model_name->select_data_by_condition('hoo_food_menu', $condition, '*' , 'id', 'DESC', '', '', array());
		
		if( empty( $menuinfo ) ){
			
			header('Content-Type: application/json');
			$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'There are no menu items added yet');
			echo json_encode($error);
			exit;
			
		} else{
			
		    header('Content-Type: application/json');
		    $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Menu Items','data' => $menuinfo);
		    echo json_encode($success);
		    exit;
			
		}
		
		
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}