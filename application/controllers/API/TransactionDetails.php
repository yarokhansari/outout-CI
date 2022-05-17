<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class TransactionDetails extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
		
		$condition = array('userid' => $userid );
		
		$paymentinfo = $this->model_name->select_data_by_condition('hoo_user_payment', $condition, '*' , '', '', '', '', $join_str);
		
		
		if( !empty( $paymentinfo ) ){
			 
			 header('Content-Type: application/json');
		     $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Transaction Details','data' => $paymentinfo);
		     echo json_encode($success);
		     exit;
			
		} else{
			
			 header('Content-Type: application/json');
		     $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'There is no payment info for this user');
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