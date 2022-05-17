<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AddTableDetails extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
		$name = $this->input->post('name');
		$capacity = $this->input->post('capacity');
	    
		$condition = 'name LIKE "' . $name . '" AND is_delete = "0"';
		
		$tableinfo = $this->model_name->select_data_by_condition('hoo_restuarant_table', $condition, '*' , '', '', '', '', array());
		
		if( !empty( $tableinfo ) ){
			
			header('Content-Type: application/json');
			$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'This table name is already added');
			echo json_encode($error);
			exit;
			
		} else{
			
			$data = array(
				'userid' => $userid,
				'name' => $name,
				'capacity' => $capacity,
				'created_at' => date('Y-m-d H:i:s'),
			);
			
			$addtable = $this->model_name->insert_data_getid($data, "hoo_restuarant_table");
			
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