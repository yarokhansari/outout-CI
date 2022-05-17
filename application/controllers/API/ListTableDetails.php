<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class ListTableDetails extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
			    
		$condition = 'is_delete = "0"';
		
		$tableinfo = $this->model_name->select_data_by_condition('hoo_restuarant_table', $condition, '*' , '', '', '', '', array());
		
		if( empty( $tableinfo ) ){
			
			header('Content-Type: application/json');
			$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'There is no table added for this restuarant');
			echo json_encode($error);
			exit;
			
		} else{
			
			header('Content-Type: application/json');
		    $tabledetails = $this->model_name->select_data_by_condition("hoo_restuarant_table", array('userid' => $userid,'is_delete' => '0'), '*', 'id', 'DESC', '', '', array());
		    $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Table Details','data' => $tabledetails);
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