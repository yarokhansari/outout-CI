<?php
/*
Author: Yogen Jajal
API Name: Search Friends
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

require 'BaseApi.php';

class SearchFriends extends BaseApi {
    function index() {
        $response = array();
        if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $user_id = $this->input->post('user_id');
            $account_type = $this->input->post('account_type');
            $is_vip = $this->input->post('is_vip');
            $search_query = $this->input->post('search_query');
            $offset = $this->input->post('offset');
            $limit = $this->input->post('limit');

            $start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;

            $usersinfo = $this->custom->getUsers($user_id, $account_type, $is_vip, $search_query, $start_index, $limit);
			
			foreach( $usersinfo as $key => $value ) {
				
				$users[$key]['user_id'] = $value['user_id'];
				$users[$key]['full_name'] = $value['full_name'];
				if( $value['dob'] != "" ){
					$users[$key]['dob'] = $value['dob'];
				}else{
					$users[$key]['dob'] = "";
				}
				$users[$key]['gender'] = $value['gender'];
				if( $value['phone_number'] != "" ){
					$users[$key]['phone_number'] = $value['phone_number'];
				}else{
					$users[$key]['phone_number'] = "";
				}
				$users[$key]['email'] = $value['email'];
				$users[$key]['username'] = $value['username'];
				if( $value['city'] != "" ){
					$users[$key]['city'] = $value['city'];
				}else{
					$users[$key]['city'] = "";
				}
				if( $value['profile_image'] != "" ){
					$users[$key]['profile_image'] = $value['profile_image'];
				}else{
					$users[$key]['profile_image'] = "";
				}
				$users[$key]['account_type'] = $value['account_type'];
				if( $value['is_vip'] != "" ){
					$users[$key]['is_vip'] = $value['is_vip'];
				}else{
					$users[$key]['is_vip'] = "";
				}
				if( $value['is_follow'] != "" ){
					$users[$key]['is_follow'] = $value['is_follow'];
				}else{
					$users[$key]['is_follow'] = "";
				}
				
				
			}

            if(!empty($users)) {
                $response = array(
                    'status' => 'Success', 
                    'errorcode' => '0', 
                    'msg' => 'Search Friends',
                    'data' => $users
                );
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'No users found.'
                );
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