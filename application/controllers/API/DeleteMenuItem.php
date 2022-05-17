<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class DeleteMenuItem extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$id = $this->input->post('id'); 
		
		$userid = $this->input->post('userid'); 
			
		$updatemenu = array(
		   'is_delete' => '1',
		   'updated_at' => date('Y-m-d H:i:s'),
		);
		
		$this->model_name->update_data($updatemenu, 'hoo_food_menu', 'id', $id);
	
		header('Content-Type: application/json');
		$menuitems = $this->model_name->select_data_by_condition("hoo_food_menu", array('userid' => $userid,'is_delete' => '0'), '*', 'id', 'DESC', '', '', array());
		$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Item deleted successfully','data' => $menuitems);
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