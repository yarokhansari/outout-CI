<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');


class OrderPayment extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  
		$id = $this->input->post('id'); //Order Id
		
		/* Fetch Order Details */
		
		$condition = 'id = '.$id.' AND order_status IN ("0","1") AND is_delete = "0"';
		
		$orderinfo = $this->model_name->select_data_by_condition('hoo_order', $condition , '*' , '', '', '', '', array());
		$fromuserinfo = $this->model_name->select_data_by_condition('hoo_users', array('id' => $orderinfo[0]['from_user_id'],'is_delete' => '0') , 'first_name,last_name,wallet' , '', '', '', '', array());
		$touserinfo = $this->model_name->select_data_by_condition('hoo_users', array('id' => $orderinfo[0]['to_user_id'],'is_delete' => '0') , 'first_name,last_name,wallet' , '', '', '', '', array());
		
		/* Deduct order amount from From User */
		
		$orderamount = $orderinfo[0]['order_amount'];
		
		$fromuserwallet = $fromuserinfo[0]['wallet'];
		
		$touserwallet = $touserinfo[0]['wallet'];
		
		if( !empty( $orderinfo )){
			
			if( $orderamount > $fromuserwallet ){
			
			//Wallet amount is less than order amount
			
			header('Content-Type: application/json');
			$response = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Your wallet balance is low. Kindly update it by adding money');
			echo json_encode($response);
			exit;
			
			} else {
				
				//Wallet amount is more than pay to touser and deduct commission for admin as 5%
				
				$comm = (float)(( $orderamount * 5 ) / 100);
				
				$remaining = (float)$orderamount - (float)$comm;
				
				
				$admindata = $this->model_name->select_data_by_condition('hoo_admin', array('id' => 1) , 'id,first_name,last_name,wallet' , '', '', '', '', array());
				
			
				if( $admindata[0]['wallet'] > 0 ){
					$wallet = (float)$admindata[0]['wallet'] + (float)$comm;
					
				} else {
					$wallet = 0 + (float)$comm;
				}
				
				//Add Commission to admin
				
				$updateadmin = array(
					'wallet' => $wallet,
					'updated_at' => date('Y-m-d h:i:s'),
				);
				$this->model_name->update_data($updateadmin, 'hoo_admin', 'id', $admindata[0]['id']);
				
				/* Update admin transaction details into payment table */

				$addpayment = array(
					'adminid' => $admindata[0]['id'],
					'amount' => $comm,
					'orderid' => $id,
					'payment_date' => date('Y-m-d H:i:s'),
					'payment_type' => '0',
					'trans_type' => '1',
					'notes' => 'Added commission for order number ' . $id,
					'created_at' => date('Y-m-d H:i:s')
				);
				
				$createpayment = $this->model_name->insert_data_getid($addpayment, "hoo_user_payment");
				
				/* Notification for from user id */
				
				$condition = array( 'user_id' => $from_user_id );

				$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
				
				$notificationData = array(
					  "title"=> "Order Payment",
					  "body" => "You have successfully made payment for ".$orderamount." with order ID " . $id,
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
					"user_id" => $orderinfo[0]['from_user_id'],
					"media_id" => 0,
					"type"=>'6',
					"description" => "You have successfully made payment for ".$orderamount." with order ID " . $id,
					"created_at" => date('Y-m-d h:i:s')
				);
				
				$fromnotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
				
				//Transfer remaining amount to From User to To User
				
				
				//Update to From User
				
				$fromuserwallet = (float)$fromuserwallet - (float)$orderamount;
				
				$updatefromuser = array(
					'wallet' => $fromuserwallet,
					'updated_at' => date('Y-m-d H:i:s')
				);
				
				$this->model_name->update_data($updatefromuser, 'hoo_users', 'id', $orderinfo[0]['from_user_id']);
				
				/* Add From Payment Details */
				
				$addfrompayment = array(
					'userid' => $orderinfo[0]['from_user_id'],
					'amount' => $orderamount,
					'orderid' => $id,
					'payment_date' => date('Y-m-d H:i:s'),
					'payment_type' => '0',
					'trans_type' => '0',
					'notes' => 'Payment deducted for order number ' . $id,
					'created_at' => date('Y-m-d H:i:s')
				);
				
				$createpayment = $this->model_name->insert_data_getid($addfrompayment, "hoo_user_payment");
				
				//Update to To User
				
				$touserwallet = (float)$touserwallet + (float)$remaining;
				
				$updatetouser = array(
					'wallet' => $touserwallet,
					'updated_at' => date('Y-m-d H:i:s')
				);
				
				$this->model_name->update_data($updatetouser, 'hoo_users', 'id', $orderinfo[0]['to_user_id']);
				
				/* Add To Payment Details */
				
				$addtopayment = array(
					'userid' => $orderinfo[0]['to_user_id'],
					'amount' => $remaining,
					'orderid' => $id,
					'payment_date' => date('Y-m-d H:i:s'),
					'payment_type' => '0',
					'trans_type' => '1',
					'notes' => 'Payment received for order number ' . $id,
					'created_at' => date('Y-m-d H:i:s')
				);
				
				$createpayment = $this->model_name->insert_data_getid($addtopayment, "hoo_user_payment");
				
				/* Notification details to ToUser */
				
				$condition = array( 'user_id' => $to_user_id );

				$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
				
				$notificationData = array(
					  "title"=> "Order Payment Received",
					  "body" => "You have successfully received payment for ".$remaining." with order ID " . $id,
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
					"user_id" => $orderinfo[0]['to_user_id'],
					"media_id" => 0,
					"description" => "You have successfully received payment for ".$remaining." with order ID " . $id,
					"created_at" => date('Y-m-d h:i:s')
				);
				
				$tonotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
				
				/* Update Order Status */
				$updateorder = array(
					'order_status' => '4',
					'updated_at' => date('Y-m-d H:i:d')
				);
				
				$this->model_name->update_data($updateorder, 'hoo_order', 'id', $id);
				
				header('Content-Type: application/json');
				$orderinfo = $this->model_name->select_data_by_condition('hoo_order', array('id' => $id) , '*' , '', '', '', '', array());
				$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Order Status Updated','data' => $orderinfo);
				echo json_encode($success);
				exit;
		
			}
			
		} else{
			
			header('Content-Type: application/json');
			$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'There is no such order exists or either payment has been done successfully');
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