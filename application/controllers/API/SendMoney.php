<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');


class SendMoney extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  
		$from_user_id = $this->input->post('from_user_id'); //From User Id
		$to_user_id = $this->input->post('to_user_id'); //From User Id
		$amount = $this->input->post('amount'); //Amount
		
		/* Fetch From and To User Details */
		
		$fromuserinfo = $this->model_name->select_data_by_condition('hoo_users', array('id' => $from_user_id,'is_delete' => '0'), 'id,first_name,last_name,wallet' , '', '', '', '', array());
		$touserinfo = $this->model_name->select_data_by_condition('hoo_users', array('id' => $to_user_id,'is_delete' => '0'), 'id,first_name,last_name,wallet' , '', '', '', '', array());
		
		/* Deduct amount from From User */
		
		
		$fromuserwallet = $fromuserinfo[0]['wallet'];
		
		$touserwallet = $touserinfo[0]['wallet'];
		
		if( $amount > $fromuserwallet ){
			
			//Wallet amount is less than order amount
			
			header('Content-Type: application/json');
			$response = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Your wallet balance is low. Kindly update it by adding money');
			echo json_encode($response);
			exit;
			
		} else {
					
			/* Update From user transaction details into payment table */
			
			$fromamount = (float)$fromuserwallet - (float)$amount;
			
			//Update From User
			
			$updatefromuser = array(
				'wallet' => $fromamount,
				'updated_at' => date('Y-m-d H:i:s')
			);
			
			$this->model_name->update_data($updatefromuser, 'hoo_users', 'id', $fromuserinfo[0]['id']);

			$addpayment = array(
				'userid' => $fromuserinfo[0]['id'],
				'amount' => $amount,
				'payment_date' => date('Y-m-d H:i:s'),
				'payment_type' => '0',
				'trans_type' => '0',
				'notes' => "You have successfully made payment for ".$amount." to " . $touserinfo[0]['first_name'] . " " . $touserinfo[0]['last_name'] .  "",
				'created_at' => date('Y-m-d H:i:s')
			);
			
			$createpayment = $this->model_name->insert_data_getid($addpayment, "hoo_user_payment");
			
			/* Notification for from user id */
			
			$condition = array( 'user_id' => $from_user_id );

			$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
			
			$notificationData = array(
				  "title"=> "Payment",
				  "body" => "You have successfully made payment for ".$amount." to " . $touserinfo[0]['first_name'] . " " . $touserinfo[0]['last_name'] .  "",
				  "mutable_content"=> true,
				  "sound"=> "Tri-tone"
			);

			$data = array();
			
			$notification = $this->model_name->sendNotification(
				$devicedetails[0]['fcmToken'],
				$data,
				$notificationData
			);
			
			$addnotification = array(
				"user_id" => $from_user_id,
				"media_id" => 0,
				"description" => "You have successfully made payment for ".$amount." to " . $touserinfo[0]['first_name'] . " " . $touserinfo[0]['last_name'] .  "",
				"type" => "6",
				"created_at" => date('Y-m-d h:i:s')
			);
			
			$fromnotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
			
			//Update to To User
			
			$toamount = (float)$touserwallet + (float)$amount;
			
			$updatetouser = array(
				'wallet' => $toamount,
				'updated_at' => date('Y-m-d H:i:s')
			);
			
			$this->model_name->update_data($updatetouser, 'hoo_users', 'id', $touserinfo[0]['id']);
			
			/* Add To Payment Details */
			
			$addtopayment = array(
				'userid' => $touserinfo[0]['id'],
				'amount' => $amount,
				'payment_date' => date('Y-m-d H:i:s'),
				'payment_type' => '0',
				'trans_type' => '1',
				'notes' => "You have successfully received payment for ".$amount." from " . $fromuserinfo[0]['first_name'] . " " . $fromuserinfo[0]['last_name'] .  "",
				'created_at' => date('Y-m-d H:i:s')
			);
			
			$createpayment = $this->model_name->insert_data_getid($addtopayment, "hoo_user_payment");
			
			/* Notification for to user id */
			
			$condition = array( 'user_id' => $to_user_id );

			$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
			
			$notificationData = array(
				  "title"=> "Payment",
				  "body" => "You have successfully received payment for ".$amount." from " . $fromuserinfo[0]['first_name'] . " " . $fromuserinfo[0]['last_name'] .  "",
			    	"type"=>"6",
				  "mutable_content"=> true,
				  "sound"=> "Tri-tone"
			);

			$data = array();
			
			$notification = $this->model_name->sendNotification(
				$devicedetails[0]['fcmToken'],
				$data,
				$notificationData
			);
			
			$addnotification = array(
				"user_id" => $to_user_id,
				"media_id" => 0,
				"description" => "You have successfully received payment for ".$amount." from " . $fromuserinfo[0]['first_name'] . " " . $fromuserinfo[0]['last_name'] .  "",
				"type" => "6",
				"created_at" => date('Y-m-d h:i:s')
			);
			
			$tonotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
			
			
			header('Content-Type: application/json');
			$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Payment was done successfully');
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