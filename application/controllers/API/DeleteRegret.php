<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class DeleteRegret extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$regretid = $this->input->post('id');
		$deleteregret = array(
		  "is_delete" => '1',
		  "updated_at" => date('Y-m-d h:i:s'),
		);
		$delete_regret = $this->model_name->update_data($deleteregret, "hoo_user_regret",'id', $regretid);
		
		if( $delete_regret ){
			
		   header('Content-Type: application/json');
		   $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Data has been deleted successfully from outout regret');
		   echo json_encode($success);
		   exit;
		
		}else{
			
		   header('Content-Type: application/json');
		   $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'Data has not been deleted successfully');
		   echo json_encode($error);
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