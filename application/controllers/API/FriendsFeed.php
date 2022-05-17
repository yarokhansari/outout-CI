<?php
/*
Author: Rutul Parikh
API Name: Friends Feed
Parameter: deviceToken, deviceType, userid
Description: API will give VIP and Non VIP Friend List
*/

require 'BaseApi.php';

class FriendsFeed extends BaseApi {
	
    function index() {
        $response = array();
		
		$count = 0;
		
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
			
            $userid = $this->input->post('user_id');
			$is_vip = $this->input->post('is_vip');
			$package = $this->input->post('type');
			
			$join_str[0] = array(
				'table' => 'hoo_friend_request',
				'join_table_id' => 'hoo_friend_request.to_user_id',
				'from_table_id' => 'hoo_users.id',
				'type' => '',
			);
			if($package==null){

				$condition = array( 'hoo_users.is_delete' => '0','hoo_friend_request.from_user_id' => $userid, 'hoo_users.is_vip' => $is_vip);
			}
			else
			{
				$condition = array( 'hoo_users.is_delete' => '0','hoo_friend_request.from_user_id' => $userid, 'hoo_users.is_vip' => $is_vip ,'package_id'=>$package);
			}
			
			
			
			$userdetails = $this->model_name->select_data_by_condition("hoo_users", $condition, 'hoo_friend_request.to_user_id,hoo_users.id as userid, hoo_users.first_name,hoo_users.last_name, hoo_users.email, hoo_users.username, hoo_users.city, hoo_users.latitude, hoo_users.longitude, hoo_users.is_vip,hoo_users.profile_image' , '', '', '', '', $join_str);
			
			if( !empty( $userdetails ) ){
				
				foreach( $userdetails as $userdetail ){
				
					$condition = array( 'is_delete' => '0','user_id' => $userdetail['userid'], 'type' => '2');
					
					$userstory = $this->model_name->select_data_by_condition("hoo_user_story", $condition, 'story' , '', '', '', '', array());
					
					$feed[$count]['userid'] = $userdetail['userid'];
					$feed[$count]['fullname'] = $userdetail['first_name']. " " . $userdetail['last_name'];
					$feed[$count]['city'] = $userdetail['city']; 
					$feed[$count]['latitude'] = $userdetail['latitude'];
					$feed[$count]['longitude'] = $userdetail['longitude'];
					$feed[$count]['profile_image'] = $userdetail['profile_image'];
					
					if( $is_vip == '0' ){
						$feed[$count]['story'] = "0";
					} else {
						if( $userstory[0]['story'] != "" ){
							$feed[$count]['story'] = "1";
						}else {
							$feed[$count]['story'] = "0";
						}
					}
					
					$friendsfeed['feeddata'] = $feed;
					$count++; 
						
					
				}
				
			}
			
			
			header('Content-Type: application/json');
			$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Friends Feed List' ,'data' => $friendsfeed);
			echo json_encode($success);
			exit;
			
        }
        else {
          $response = array(
            'status' => 'failure', 
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