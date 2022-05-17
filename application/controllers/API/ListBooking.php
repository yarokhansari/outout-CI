<?php
/*
Author: Rutul Parikh
API Name: ListBooking
Parameter: apiKey, userid
Description: API will list out all bookings based on userid.
*/

require 'BaseApi.php';

class ListBooking extends BaseApi {
	
    function index() {
		
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $response = array();
  
		  $userid = $this->input->post('userid');
		  
		  $condition = "from_user_id = $userid AND status IN('0','1') AND is_delete = '0'";
			  
		  $tablebooking = $this->model_name->select_data_by_condition('hoo_table_booking', $condition , '*' ,'id' , 'DESC' ,'', '', array());
		  
	  
		  if( !empty( $tablebooking ) ){
			  
			  foreach( $tablebooking as $table ){
				  
				  /* Table Information */
			  
				  $join_str[0] = array(
					'table' => 'hoo_table_booking',
					'join_table_id' => 'hoo_table_booking.tableid',
					'from_table_id' => 'hoo_restuarant_table.id',
					'type' => '',
				  );
			  
				 
				  $condition = "hoo_restuarant_table.id = ".$table['tableid']." AND hoo_restuarant_table.is_delete = '0'";
				  
				  $tableinfo = $this->model_name->select_data_by_condition('hoo_restuarant_table', $condition, 'hoo_restuarant_table.name', '', '', '', '', $join_str);
				  
				  /* User Information */
				  
				  $join_str[0] = array(
					'table' => 'hoo_table_booking',
					'join_table_id' => 'hoo_table_booking.from_user_id',
					'from_table_id' => 'hoo_users.id',
					'type' => '',
				  );
				  
				  $condition = "hoo_users.id = ".$table['from_user_id']." AND hoo_users.is_delete = '0'";
				  
				  $userinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, 'hoo_users.first_name,hoo_users.last_name,hoo_users.email', '', '', '', '', $join_str);
				  
				  $bookinginfo['id'] = $table['id'];
				  $bookinginfo['tableid'] = $table['tableid'];
				  $bookinginfo['tablename'] = $tableinfo[0]['name'];
				  $bookinginfo['userid'] = $table['from_user_id'];
				  $bookinginfo['fullname'] = $userinfo[0]['first_name'] . " " . $userinfo[0]['last_name'];
				  $bookinginfo['email'] = $userinfo[0]['email'];
				  $bookinginfo['number_of_person'] = $table['number_of_person'];
				  $bookinginfo['visit_time'] = $table['visit_time'];
				  $bookinginfo['status'] = $table['status'];
				  
				  $infodetails[] = $bookinginfo;
				  
				 
				  
			  }
			  
			   header('Content-Type: application/json');
			   $response = array('status' => 'success', 'errorcode' => '0','msg' => 'Booking List','data' => $infodetails);
			   echo json_encode($response);
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