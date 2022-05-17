<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AddMoneyToWallet extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
		$amount = $this->input->post('amount');
		$payment_ref_id = $this->input->post('payment_ref_id');
		
		$condition = 'is_delete = "0" AND id = ' . $userid;
		
		$columns = 'id,first_name,last_name,wallet';
	
		$userinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
		
		if( !empty($userinfo) ){
			
			if( $userinfo[0]['wallet'] == '0.00' ){
				$userinfo[0]['wallet'] = $amount;
			} else {
				$userinfo[0]['wallet'] += $amount;
			}
			
			/* Update balance in wallet */
			
			$update = array(
				'wallet' => $userinfo[0]['wallet'],
				'updated_at' => date('Y-m-d h:i:s'),
			);
			
			$this->model_name->update_data($update, 'hoo_users', 'id', $userid);
			
			/* Add data into payment transaction */
			
			$payment = array(
				'userid' => $userid,
				'payment_ref_id' => $payment_ref_id,
				'amount' => $amount,
				'payment_date' => date('Y-m-d h:i:s'),
				'payment_type' => '1',
				'trans_type' => '1',
				'notes' => 'Added funds into wallet',
				'created_at' => date('Y-m-d h:i:s')
			);
			
			$submitpayment = $this->model_name->insert_data_getid($payment, "hoo_user_payment");
			
			$updateddata = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
			
			header('Content-Type: application/json');
		    $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Updated Wallet By £' . $amount ,'data' => $updateddata);
		    echo json_encode($success);
		    exit;
			
		} else {
			
			header('Content-Type: application/json');
		    $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'There is no such user exists');
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