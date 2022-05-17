<?php
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class MakePayment extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $userid = $this->input->post('userid');
		  $packageid = $this->input->post('packageid');
		  $payment_ref_id = $this->input->post('payment_ref_id');
		  $payment_date = date('Y-m-d h:i:s');
		  
		  $userpayment = array(
			"userid" => $userid,
			"packageid" => $packageid,
			"payment_ref_id"=> $payment_ref_id,
			"payment_date" => $payment_date,
			"created_at" => date('Y-m-d h:i:s')
		  );
  		  $userpaymentid = $this->model_name->insert_data_getid($userpayment, "hoo_user_payment");
		  
		  if( $userpaymentid ){
			  
			  $userinfo = $this->model_name->select_data_by_condition('hoo_users',array('is_delete' => '0','id' => $userid ), '*' ,'' , '' ,'', '', array());
			  
			  $packageinfo = $this->model_name->select_data_by_condition('hoo_packages',array('is_delete' => '0','id' => $packageid ), '*' ,'' , '' ,'', '', array());
			  
			  $payment['id'] = $userpaymentid;
			  $payment['username'] = $userinfo[0]['first_name'] . " " . $userinfo[0]['last_name'];
			  $payment['packagename'] = $packageinfo[0]['name'];
			  $payment['payment_ref_id'] = $payment_ref_id;
			  $payment['payment_date'] = $payment_date;
			  
			  header('Content-Type: application/json');
			  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Payment Done Successfully' ,'data' => $payment);
			  echo json_encode($success);
			  exit;
			  
			  
		  }else{
		  
			  header('Content-Type: application/json');
			  $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Payment is unsuccessful');
			  echo json_encode($error);
			  exit;
				  
		  }
		  
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }
  }
}