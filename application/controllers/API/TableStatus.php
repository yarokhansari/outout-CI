<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class TableStatus extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  
		$id = $this->input->post('id');
		$table_status = $this->input->post('table_status');
		
		$updatetable = array(
			'status' => $table_status,
			'updated_at' => date('Y-m-d H:i:s'),
		);
		
		$this->model_name->update_data($updatetable, 'hoo_table_booking', 'id', $id);
		
		header('Content-Type: application/json');
		$tableinfo = $this->model_name->select_data_by_condition('hoo_table_booking', array('id' => $id,'is_delete' => '0') , '*' , '', '', '', '', array());
		$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Table Status Updated','data' => $tableinfo);
		echo json_encode($success);
		exit;
		
		
		
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}