<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AddBookingTable extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
			  
		$from_user_id = $this->input->post('from_user_id');
		$to_user_id = $this->input->post('to_user_id');
		$tableid = $this->input->post('tableid');
		$number_of_person = $this->input->post('number_of_person');
		$visit_time = $this->input->post('visit_time');
				
		$tableinfo = $this->model_name->select_data_by_condition('hoo_restuarant_table', array('id' => $tableid,'is_delete' => '0') , '*' , '', '', '', '', array());
		
		if( !empty( $tableinfo ) ){
			if( $tableinfo[0]['capacity'] < $number_of_person ){
				
				header('Content-Type: application/json');
				$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'Table capacity is less than the persons');
				echo json_encode($error);
				exit;
				
			} else {
				
				 /* Check with booking time */
				 
				 $condition = 'visit_time LIKE "%'.$visit_time.'%" AND is_delete = "0" AND id = '.$tableid.'';

				 $bookinginfo = $this->model_name->select_data_by_condition('hoo_table_booking', $condition , '*' , '', '', '', '', array());
				 
				 if( !empty( $bookinginfo ) ){

					header('Content-Type: application/json');
					$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'This table is already booked for this particular time');
					echo json_encode($error);
					exit;
					 
				 } else{
					 
					 $addbooking = array(
						'from_user_id' => $from_user_id,
						'to_user_id' => $to_user_id,
						'tableid' => $tableid,
						'number_of_person' => $number_of_person,
						'visit_time' => $visit_time,
						'created_at' => date('Y-m-d H:i:s'),
					);
					
					$createbooking = $this->model_name->insert_data_getid($addbooking, "hoo_table_booking");
					
					
					$join_str[0] = array(
						'table' => 'hoo_users',
						'join_table_id' => 'hoo_users.id',
						'from_table_id' => 'hoo_table_booking.from_user_id',
						'type' => '',
					);
					
					$condition = array( 'hoo_table_booking.id' => $createbooking );
					
					$bookingdetails = $this->model_name->select_data_by_condition('hoo_table_booking', $condition , 'hoo_users.first_name,hoo_users.last_name,hoo_table_booking.number_of_person,hoo_table_booking.visit_time,hoo_table_booking.status,' , '', '', '', '', $join_str);
					
					header('Content-Type: application/json');
					$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Your table is booked successfully','data' => $bookingdetails);
					echo json_encode($success);
					exit;
					 
				 }
				
			}
		}
		
		
		$createdorder = $this->model_name->insert_data_getid($addorder, "hoo_order");
		
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