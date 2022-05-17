<?php
/*
Author: Rutul Parikh
API Name: ListBusinessOrderBooking
Parameter: apiKey, userid
Description: API will list out all order bookings based on userid.
*/

require 'BaseApi.php';

class ListBusinessOrderBooking extends BaseApi {
	
    function index() {
		
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $response = array();
		  
		  
  
		  $userid = $this->input->post('userid');
		  $status = $this->input->post('status');
		//$orderdate = date('Y-m-d',strtotime($this->input->post('order_date')));
		 $today=date('Y-m-d');

		  
		//   $condition = "to_user_id = $userid AND is_delete = '0' AND '`order_date`>=$orderdate'";
			  if($status==null)
			  {
				$orderbooking = $this->model_name->select_data_by_condition('hoo_order', array('to_user_id'=>$userid) , '*' ,'' , '' ,'', '', array());

			  }
			  else{
		  $orderbooking = $this->model_name->select_data_by_condition('hoo_order', array('to_user_id'=>$userid,'order_date>='=>$today,'order_status'=>$status) , '*' ,'' , '' ,'', '', array());
			  }
		 
		  
		  if( !empty( $orderbooking ) ){
			  
			  foreach( $orderbooking as $order ){
				  
				  $info['id'] = $order['id'];
				  $userinfo = $this->model_name->select_data_by_condition('hoo_users', array('id' => $order['from_user_id'], 'is_delete' => '0') , 'id,first_name,last_name' , '', '', '', '', array());
				  $info['from_user_id'] = $userinfo[0]['id'];
				  $info['from_name'] = $userinfo[0]['first_name'] . " " . $userinfo[0]['last_name'];
				  $items = unserialize($order['items_details']);
				  $count = 0;
				  foreach( $items as $item ){
					  $id = $item['id'];
					  $item_details = $this->model_name->select_data_by_condition('hoo_food_menu', array('id' => $id, 'is_delete' => '0') , 'name,price' , '', '', '', '', array());
					  $menu['name'] = $item_details[0]['name'];
					  $menu['price'] = $item_details[0]['price'];
					  $menu['qty'] = $item['qty'];
					  $info['items_details'][$count] = $menu;
					  $count++;  
					  
				  }
				
				  $info['order_status'] = $order['order_status'];
				  $info['order_date'] = $order['order_date'];
				  $info['order_amount'] = $order['order_amount'];
				  
				  $orderdetails[] = $info;
					
				  
			  }
			  
			  header('Content-Type: application/json');
			  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Order Details','data' => $orderdetails);
			  echo json_encode($success);
			  exit;
			  
		  } else {
			  
			  header('Content-Type: application/json');
			  $response = array('status' => 'success','errorcode' => '0','msg' => 'There are no bookings yet.');
			  echo json_encode($response);
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

?>