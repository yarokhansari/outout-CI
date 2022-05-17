<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class OrderDetails extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		 
		$count = 0; 
	  
		$id = $this->input->post('id');
		
		$orderinfo = $this->model_name->select_data_by_condition('hoo_order', array('id' => $id, 'is_delete' => '0') , '*' , '', '', '', '', array());
		
		if( !empty( $orderinfo ) ){
			
			foreach( $orderinfo as $order ){
				
				$info['id'] = $order['id'];
				$userinfo = $this->model_name->select_data_by_condition('hoo_users', array('id' => $order['from_user_id'], 'is_delete' => '0') , 'first_name,last_name' , '', '', '', '', array());
				$info['from_user_id'] = $order['from_user_id'];
				$info['from_name'] = $userinfo[0]['first_name'] . " " . $userinfo[0]['last_name'];
				$items = unserialize($order['items_details']);
				foreach( $items as $item ){
					
					$item_details = $this->model_name->select_data_by_condition('hoo_food_menu', array('id' => $item['id'], 'is_delete' => '0') , 'name,price' , '', '', '', '', array());
					$menu['name'] = $item_details[0]['name'];
					$menu['price'] = $item_details[0]['price'];
					$menu['qty'] = $item['qty'];
					$info['items_details'][$count] = $menu;
					
					$count++;
					
				}
				$info['order_date'] = $order['order_date'];
				if( $order['order_status'] == '0' ){
					$info['order_status'] = 'Received';
				} else if( $order['order_status'] == '1' ) {
					$info['order_status'] = 'Accepted';
				} else if( $order['order_status'] == '2' ) {
					$info['order_status'] = 'Prepared';
				} else {
					$info['order_status'] = 'Cancelled';
				}

				$info['order_amount'] = $order['order_amount'];				
			}
			
			header('Content-Type: application/json');
			$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Order Details for Order No. ' . $id ,'data' => $info);
			echo json_encode($success);
			exit;
			
		} else {
			
			header('Content-Type: application/json');
			$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'No order exists for this particular id');
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