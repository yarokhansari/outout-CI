<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AddOrder extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$orderamount = 0;
		  
		$from_user_id = $this->input->post('from_user_id');
		$to_user_id = $this->input->post('to_user_id');
		$item_details = $this->input->post('item_details');
	    
		$items = json_decode($item_details,true);
		
		
		foreach( $items as $item ) {
		
			$priceinfo = $this->model_name->select_data_by_condition('hoo_food_menu', array('id' => $item['id'],'is_delete' => '0'), 'price' , '', '', '', '', array());
			$item_amount = (float)$priceinfo[0]['price'] * $item['qty'];
			$orderamount += $item_amount;
		}
		
		$items = serialize($items);
		
		$addorder = array(
			'from_user_id' => $from_user_id,
			'to_user_id' => $to_user_id,
			'items_details' => $items,
			'order_date' => date('Y-m-d H:i:s'),
			'order_status' => '0',
			'order_amount' => $orderamount,
			'created_at' => date('Y-m-d H:i:s'),
		);
		$createdorder = $this->model_name->insert_data_getid($addorder, "hoo_order");
		
		/* Create Notification for From User ID */
		
		$condition = array( 'user_id' => $from_user_id );

		$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
		
		$notificationData = array(
			  "title"=> "Order Created",
			  "body" => "Your order has been created with order ID " . $createdorder,
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
            "description" => 'Your order has been created',
            "created_at" => date('Y-m-d h:i:s')
        );
		
		$fromnotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
		
		/* Create Notification for To User ID */
		
		$condition = "";
		$devicedetails = array();
		
		$condition = array( 'user_id' => $to_user_id );

		$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
		
		$notificationData = array(
			  "title"=> "Order Received",
			  "body" => "You have received 1 order",
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
            "description" => 'You have received 1 order',
            "created_at" => date('Y-m-d h:i:s')
        );
		
		$tonotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
		
		header('Content-Type: application/json');
		$orderinfo = $this->model_name->select_data_by_condition('hoo_order', array('id' => $createdorder) , '*' , '', '', '', '', array());
		$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Order Created successfully','data' => $orderinfo);
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