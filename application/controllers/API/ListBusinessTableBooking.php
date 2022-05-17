<?php
/*
Author: Rutul Parikh
API Name: ListBusinessOrderBooking
Parameter: apiKey, userid
Description: API will list out all order bookings based on userid.
*/

require 'BaseApi.php';

class ListBusinessTableBooking extends BaseApi {
	
    function index() {
		
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $response = array();
		  
		  $count = 0;
  
		  $userid = $this->input->post('userid');
		  
		  $condition = "to_user_id = $userid AND is_delete = '0'";
			  
		  $tablebooking = $this->model_name->select_data_by_condition('hoo_table_booking', $condition , '*' ,'id' , 'DESC' ,'', '', array());
		  
		  if( !empty( $tablebooking ) ){
			  
			  foreach( $tablebooking as $table ){
				  
				  $info['id'] = $table['id'];
				  $userinfo = $this->model_name->select_data_by_condition('hoo_users', array('id' => $table['from_user_id'], 'is_delete' => '0') , 'id,first_name,last_name' , '', '', '', '', array());
				  $info['from_user_id'] = $userinfo[0]['id'];
				  $info['from_name'] = $userinfo[0]['first_name'] . " " . $userinfo[0]['last_name'];
				  $tableinfo = $this->model_name->select_data_by_condition('hoo_restuarant_table', array('id' => $table['tableid'], 'is_delete' => '0') , 'name' , '', '', '', '', array());
				  $info['tableid'] = $table['tableid'];
				  $info['tablename'] = $tableinfo[0]['name'];
				  $info['number_of_person'] = $table['number_of_person'];
				  $info['visit_time'] = $table['visit_time'];
				  $info['status'] = $table['status'];
				 
				  $tabledetails[] = $info;	
				  
			  }
			  
			  header('Content-Type: application/json');
			  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Table Details','data' => $tabledetails);
			  echo json_encode($success);
			  exit;
			  
		  } else {
			  
			  header('Content-Type: application/json');
			  $response = array('status' => 'success','errorcode' => '0','msg' => 'There are no table bookings yet.');
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