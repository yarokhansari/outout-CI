<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class OrderStatus extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  
		$id = $this->input->post('id');
		$order_status = $this->input->post('order_status');
		
		$updateorder = array(
			'order_status' => $order_status,
			'updated_at' => date('Y-m-d H:i:s'),
		);
		


		$this->model_name->update_data($updateorder, 'hoo_order', 'id', $id);
		
		header('Content-Type: application/json');
		$orderinfo = $this->model_name->select_data_by_condition('hoo_order', array('id' => $id) , '*' , '', '', '', '', array());

		if($order_status==5)
		{

    		$userid=$orderinfo[0]['from_user_id'];
			$touser=$orderinfo[0]['to_user_id'];
			$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", array( 'user_id' =>$userid ), '*' , '', '', '', '', array());
			$userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $touser, 'is_delete' => '0'), '*' , '', '', '', '', array());
    		$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
			$image=$userdetails[0]['profile_image'];
			$notificationData = array(
				"title"=> "Rate Your Experiance!",
				"body" => "Click me to rate",
				"mutable_content"=> true,
				"sound"=> "Tri-tone",
				'image'=> $image,
				'style' => 'picture',
				'picture'=>$image,
			  );
		  
			  $data = array(
				'notification_type' => '7',
				'Rating'=>$orderinfo[0]['to_user_id'],
				'name'=>$fullname,
				'image'=>$image,
			  );
			  
			  $notification = $this->model_name->sendNotification(
				$devicedetails[0]['fcmToken'],
				$data,
				$notificationData
			  );
		}

		$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Order Status Updated','data' => $orderinfo );
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