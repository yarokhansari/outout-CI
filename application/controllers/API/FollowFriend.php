<?php
/*
Author: Rutul Parikh
API Name: Follow Friend
Parameter: deviceToken, deviceType, from_user_id, to_user_id,is_follow
Description: API will follow and unfollow the users
*/

require 'BaseApi.php';

class FollowFriend extends BaseApi {
    function index() {
		if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
			
			 $from_user_id = $this->input->post('from_user_id');
             $to_user_id = $this->input->post('to_user_id');
			 $is_follow = $this->input->post('is_follow');
			 
			 $condition = array('from_user_id' => $from_user_id, 'to_user_id' => $to_user_id);
			 
			 $info = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , '*' ,'' , '' ,'', '', array());
			 
			 if( !empty( $info ) ){ //Record exists with from user id and to user id
			 
				if( $is_follow == '0' ){
				 
					 $updatefromfriend = array(
						'is_follow' => '0',
						'updated_at' => date('Y-m-d h:i:s'),
					 );
					 $this->model_name->update_data($updatefromfriend, 'hoo_friend_request', 'id', $info[0]['id']);
					 
					 /* To User Info */

					$tocondition = array('from_user_id' => $to_user_id, 'to_user_id' => $from_user_id);
					
					$toinfo = $this->model_name->select_data_by_condition('hoo_friend_request', $tocondition , '*' ,'' , '' ,'', '', array());
					
					$updatetofriend = array(
						'is_follow' => '0',
						'updated_at' => date('Y-m-d h:i:s'),
					);
					$this->model_name->update_data($updatetofriend, 'hoo_friend_request', 'id', $toinfo[0]['id']);
					 
					$updated_data = $this->model_name->select_data_by_condition('hoo_friend_request', 'id IN ('.$info[0]['id'].','.$toinfo[0]['id'].')', '*' ,'' , '' ,'', '', array());

					
					$response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'You are unfollowing your friend','data' => $updated_data);
					 
				 } else{
					 
					 $updatefromfriend = array(
						'is_follow' => '1',
						'updated_at' => date('Y-m-d h:i:s'),
					 );
					 
					 $this->model_name->update_data($updatefromfriend, 'hoo_friend_request', 'id', $info[0]['id']);
					 
					 /* To User Info */
					 
					 $tocondition = array('from_user_id' => $to_user_id, 'to_user_id' => $from_user_id);
					
					 $toinfo = $this->model_name->select_data_by_condition('hoo_friend_request', $tocondition , '*' ,'' , '' ,'', '', array());
					 
					 $updatetofriend = array(
						'is_follow' => '1',
						'updated_at' => date('Y-m-d h:i:s'),
					 );
					 
					 $this->model_name->update_data($updatetofriend, 'hoo_friend_request', 'id', $toinfo[0]['id']);
					 
					 $updated_data = $this->model_name->select_data_by_condition('hoo_friend_request', 'id IN ('.$info[0]['id'].','.$toinfo[0]['id'].')' , '*' ,'' , '' ,'', '', array());
					 $response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'You will start following your friend','data' => $updated_data);

				 }
				 
			 } else { // Record does not exists with from user id and to user id , so insert the data with the is_follow status 
			 
					$fromdata = array(
						'from_user_id' => $from_user_id,
						'to_user_id' => $to_user_id,
						'is_follow' => $is_follow,
						'created_at' => date('Y-m-d H:i:s'),
					);
					
					$fromid = $this->model_name->insert_data_getid($fromdata, "hoo_friend_request");
					
					$todata = array(
						'from_user_id' => $to_user_id,
						'to_user_id' => $from_user_id,
						'is_follow' => $is_follow,
						'created_at' => date('Y-m-d H:i:s'),
					);
					
					$toid = $this->model_name->insert_data_getid($todata, "hoo_friend_request");
					
					$inserted_data = $this->model_name->select_data_by_condition('hoo_friend_request', 'id IN ('.$fromid.','.$toid.')' , '*' ,'' , '' ,'', '', array());
					$response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'You will start following your friend','data' => $inserted_data);
				 
			 }
			
		} else{
			
			$response = array('status' => 'Failed', 'errorcode' => '2',	'msg' => 'Access Token is incorrect');
			
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
    }
}