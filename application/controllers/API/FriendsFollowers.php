<?php
/*
Author: Rutul Parikh
API Name: FriendsFollowers
Parameter: deviceToken, deviceType, from_user_id, to_user_id,is_follow
Description: API will give list of friends to who we are following
*/

require 'BaseApi.php';

class FriendsFollowers extends BaseApi {
    function index() {
		if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
			
			$userid = $this->input->post('userid');
			
			$join_str[0] = array(
				'table' => 'hoo_users',
				'join_table_id' => 'hoo_users.id',
				'from_table_id' => 'hoo_friend_request.to_user_id',
				'type' => '',
			);
						
	
			$condition = 'hoo_friend_request.is_delete = "0" AND hoo_friend_request.is_follow = "1" AND hoo_friend_request.from_user_id = ' . $userid;
			
			$columns = 'hoo_users.id,hoo_users.first_name,hoo_users.last_name,hoo_users.dob,hoo_users.gender,hoo_users.phone_number,hoo_users.email,hoo_users.username,hoo_users.city,hoo_users.profile_image,hoo_users.account_type,hoo_users.is_vip,hoo_friend_request.status,hoo_friend_request.is_follow';
			
			$followersdata = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , $columns , '', '', '', '', $join_str); 
			
			foreach( $followersdata as $follow ){
				
				$info['user_id'] = $follow['id'];
				$info['full_name'] = $follow['first_name'] . " " . $follow['last_name'];
				$info['dob'] = $follow['dob'];
				$info['gender'] = $follow['gender'];
				$info['phone_number'] = $follow['phone_number'];
				$info['email'] = $follow['email'];
				$info['username'] = $follow['username'];
				$info['city'] = $follow['city'];
				$info['profile_image'] = $follow['profile_image'];
				$info['account_type'] = $follow['account_type'];
				$info['is_vip'] = $follow['is_vip'];
				$info['status'] = $follow['status'];
				$info['is_follow'] = $follow['is_follow'];
				
				$userdata[] = $info;
				
				
			}
			
			if( !empty( $userdata ) ){
				
				header('Content-Type: application/json');
				$response = array(
					'status' => 'Success', 
					'errorcode' => '0', 
					'msg' => 'Friend Followers',
					'data' => $userdata
				);
				echo json_encode($response);
			    exit;
				
			} else{
				
				header('Content-Type: application/json');
			    $response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'There are no followers', 'data' => array());
			    echo json_encode($response);
			    exit;
				
			}

        }
     }
}