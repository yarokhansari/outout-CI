<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class UpdatePlan extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
		$packageid = $this->input->post('packageid');
		$paymenttype = $this->input->post('paymenttype');
		
		$condition = 'is_delete = "0" AND id = ' . $userid;
		
		$columns = 'id,first_name,last_name,wallet';
	
		$userinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
		
		if( !empty($userinfo) ){
			
			/* Package Details */

			$condition = "is_delete = '0' AND id = $packageid";
			
			$packageinfo = $this->model_name->select_data_by_condition('hoo_packages', $condition, '*', '', '', '', '', $join_str);
		
			if( $paymenttype == 'wallet' ){
				
				if( $userinfo[0]['wallet'] < $packageinfo[0]['price'] ){
					
					header('Content-Type: application/json');
					$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'Your wallet is having low balance. Please update it to upgrade your plan');
					echo json_encode($error);
					exit;
					
				} else {
					
					/* Deduct amount from wallet */
					
					$wallet = $userinfo[0]['wallet'] - $packageinfo[0]['price'];
					
					$updateplan = array(
						'payment_status' => '1',
						'package_id' => $packageid,
						'wallet' => $wallet,
						'updated_at' => date('Y-m-d h:i:s'),
					);
					
					$this->model_name->update_data($updateplan, 'hoo_users', 'id', $userid);
					
					/* Add data into payment transaction */
				
					$payment = array(
						'userid' => $userid,
						'packageid' => $packageid,
						'payment_ref_id' => '',
						'amount' => $packageinfo[0]['price'],
						'payment_type' => '0',
						'trans_type' => '0',
						'payment_date' => date('Y-m-d h:i:s'),
						'notes' => 'Upgrade subscription to ' . $packageinfo[0]['name'],
						'created_at' => date('Y-m-d h:i:s')
					);
					
					$submitpayment = $this->model_name->insert_data_getid($payment, "hoo_user_payment");
					
					$updateddata = $this->model_name->select_data_by_condition('hoo_users', array('is_delete' => '0', 'id' => $userid ), '*' , '', '', '', '', $join_str);
					
					header('Content-Type: application/json');
					$success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Upgrade Plan Successfully','data' => $updateddata);
					echo json_encode($success);
					exit;
					
				}
				
				
			} else {
				
				$payment = array(
					'userid' => $userid,
					'packageid' => $packageid,
					'payment_ref_id' => '',
					'amount' => $packageinfo[0]['price'],
					'payment_type' => '1',
					'trans_type' => '0',
					'payment_date' => date('Y-m-d h:i:s'),
					'notes' => 'Upgrade subscription to ' . $packageinfo[0]['name'],
					'created_at' => date('Y-m-d h:i:s')
				);
				
				$submitpayment = $this->model_name->insert_data_getid($payment, "hoo_user_payment");
				
				header('Content-Type: application/json');
				$success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Upgrade Plan Successfully');
				echo json_encode($success);
				exit;
				
			}
			
		
			
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