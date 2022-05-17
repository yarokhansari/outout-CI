<?php
/*
Author: Rutul Parikh
API Name: ListUserOrderBooking
Parameter: apiKey, userid
Description: API will list out all order bookings based on userid.
*/

require 'BaseApi.php';

class ListUserOrderBooking extends BaseApi {
	
    function index() {
		
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $response = array();
		  
		  $count = 0;
  
		  $userid = $this->input->post('userid');
		  
		  $condition = "from_user_id = $userid AND is_delete = '0'";
			  
		  $orderbooking = $this->model_name->select_data_by_condition('hoo_order', $condition , '*' ,'id' , 'DESC' ,'', '', array());
		  
		  if( !empty( $orderbooking ) ){
			  
			  foreach( $orderbooking as $order ){
				  
				  $info['id'] = $order['id'];
				  $userinfo = $this->model_name->select_data_by_condition('hoo_users', array('id' => $order['to_user_id'], 'is_delete' => '0') , 'id,first_name,last_name' , '', '', '', '', array());
				  $info['to_user_id'] = $userinfo[0]['id'];
				  $info['to_name'] = $userinfo[0]['first_name'] . " " . $userinfo[0]['last_name'];
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
				  $info['order_date'] = date('Y-m-d H:i:s');
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