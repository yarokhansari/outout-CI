<?php
/*
Author: Rutul Parikh
API Name: ListBooking
Parameter: apiKey, userid
Description: API will list out all bookings based on userid.
*/

require 'BaseApi.php';

class ListCheckinUser extends BaseApi {
	
    function index() {
		
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $response = array();
  
          $to_user_id = $this->input->post('to_user');
		  
		//   $condition = "from_user_id = $userid AND status IN('0','1') AND is_delete = '0'";
		//   $condition = "from_user_id=$from_user_id ANDs  is_delete = '0'";
			  
		  $businessrating = $this->model_name->select_data_by_condition('hoo_checkin', array('to_user'=>$to_user_id) , '' ,'from_user','' ,'', '', array());
		  
	  
		  if( !empty( $businessrating ) ){
			  
			  foreach( $businessrating as $rating ){
				  
				  /* users */
			  
				  $join_str[0] = array(
					'table' => 'hoo_checkin',
					'join_table_id' => 'hoo_users.id',
					'from_table_id' => 'hoo_checkin.from_user',
					'type' => '',
				  );
			  
				 
				  $condition = "hoo_users.id=".$rating['from_user']."";
				  
				  $tableinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, 'first_name,last_name,profile_image', '', '', '', '', $join_str);

                  
		
              
            	  $bookinginfo['Id'] = $rating['id'];
				  $bookinginfo['From Userid'] = $rating['from_user'];
                  $bookinginfo['User Name'] = $tableinfo[0]['first_name'] . " " . $tableinfo[0]['last_name'];
                  $bookinginfo['Checkin'] = $rating['checkin'];
				  $bookinginfo['Profile_image'] = $tableinfo[0]['profile_image'];
				  $bookinginfo['Date_time'] = $rating['created_at'];
				
                  
                  $infodetails[] = $bookinginfo;
			  }
			  
			   header('Content-Type: application/json');
			   $response = array('status' => 'success', 'errorcode' => '0','msg' => 'users List','data' => $infodetails);
			   echo json_encode($response);
			   exit;	
			  
			 
			  
		  } else {
			  
			  header('Content-Type: application/json');
			  $response = array('status' => 'success','errorcode' => '0','msg' => 'There are no Checkin yet.');
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