<?php
/*
Author: Rutul Parikh
API Name: Requested Friend
Parameter: deviceToken, deviceType, from_user_id
Description: API will list out all friends to who we have send the request.
*/

require 'BaseApi.php';

class RequestedFriends extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
			
			$from_user_id = $this->input->post('from_user_id');
			
			$join_str[0] = array(
				'table' => 'hoo_users',
				'join_table_id' => 'hoo_users.id',
				'from_table_id' => 'hoo_friend_request.to_user_id',
				'type' => '',
			);
						
	
			$condition = 'hoo_friend_request.is_delete = "0" AND hoo_friend_request.status = "3" AND hoo_friend_request.from_user_id = ' . $from_user_id;
			
			$columns = 'hoo_users.id,hoo_users.first_name,hoo_users.last_name,hoo_users.dob,hoo_users.gender,hoo_users.phone_number,hoo_users.email,hoo_users.username,hoo_users.city,hoo_users.profile_image,hoo_users.account_type,hoo_users.is_vip,hoo_friend_request.status,hoo_friend_request.is_follow';
		
			
			$requestdata = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , $columns , '', '', '', '', $join_str); 
			
			foreach( $requestdata as $request ){
				
				$info['user_id'] = $request['id'];
				$info['full_name'] = $request['first_name'] . " " . $request['last_name'];
				$info['dob'] = $request['dob'];
				$info['gender'] = $request['gender'];
				$info['phone_number'] = $request['phone_number'];
				$info['email'] = $request['email'];
				$info['username'] = $request['username'];
				$info['city'] = $request['city'];
				$info['profile_image'] = $request['profile_image'];
				$info['account_type'] = $request['account_type'];
				$info['is_vip'] = $request['is_vip'];
				$info['status'] = $request['status'];
				$info['is_follow'] = $request['is_follow'];
				
				$userdata[] = $info;
				
				
			}
			
			if( !empty( $userdata ) ){
				
				$response = array(
					'status' => 'Success', 
					'errorcode' => '0', 
					'msg' => 'Friend Request Send',
					'data' => $userdata
				);
				
			} else{
				
				header('Content-Type: application/json');
			    $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'There is no friend request receive');
			    echo json_encode($error);
			    exit;
				
			}
          

          
        }
        else {
          $response = array(
            'status' => 'Failed', 
            'errorcode' => '2',
            'msg' => 'Access Token is incorrect'
        );
      }

      header('Content-Type: application/json');
      echo json_encode($response);
      exit;
  }
}

?>