<?php
/*
Author: Yarokh Ansari
API Name: Rating List
Parameter: apiKey, userid
Description: API will list out all bookings based on userid.
*/

require 'BaseApi.php';

class ListRatings extends BaseApi {
	
    function index() {
		
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $response = array();
  
          $to_user_id = $this->input->post('to_user_id');

			  
		  $businessrating = $this->model_name->select_data_by_condition('hoo_rating', array('to_user_id'=>$to_user_id) , '' ,'created_at','DESC' ,'', '', array());
		  
	  
		  if( !empty( $businessrating ) ){
			  
			  foreach( $businessrating as $rating ){
				  
				  /* users */
			  
				  $join_str[0] = array(
					'table' => 'hoo_rating',
					'join_table_id' => 'hoo_users.id',
					'from_table_id' => 'hoo_rating.from_user_id',
					'type' => '',
				  );
			  
				 
				  $condition = "hoo_users.id=".$rating['from_user_id']."";
				  
				  $tableinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, 'first_name,last_name,profile_image', '', '', '', '', $join_str);

                  
		
              
            	  $bookinginfo['Id'] = $rating['id'];
				  $bookinginfo['Userid'] = $rating['from_user_id'];;
                  $bookinginfo['User Name'] = $tableinfo[0]['first_name'] . " " . $tableinfo[0]['last_name'];
				  $bookinginfo['Rating'] = $rating['rating'];
				  $bookinginfo['Comments'] = $rating['comments'];
				  $bookinginfo['Profile_image'] = $tableinfo[0]['profile_image'];
				
                  
                  $infodetails[] = $bookinginfo;
			  }
			  
			   header('Content-Type: application/json');
			   $response = array('status' => 'success', 'errorcode' => '0','msg' => 'users List','data' => $infodetails);
			   echo json_encode($response);
			   exit;	
			  
			 
			  
		  } else {
			  
			  header('Content-Type: application/json');
			  $response = array('status' => 'success','errorcode' => '0','msg' => 'There are no Advertising yet.');
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