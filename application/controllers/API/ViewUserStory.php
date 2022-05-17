<?php
/*
Author: Rutul Parikh
API Name: View User Story
Parameter: accessToken , user_id , type
Description: API will list out all stories
*/

require 'BaseApi.php';

class ViewUserStory extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {

			$user_id = $this->input->post('user_id');
			$type = $this->input->post('type');
			
			$type = "'" . implode( "','", $type ) . "'";
			
			$condition = 'hoo_user_story.type IN ('.$type.') AND hoo_user_story.is_delete = "0" AND hoo_user_story.user_id = '.$user_id;
			

			$join_str[0] = array(
					'table' => 'hoo_user_story',
					'join_table_id' => 'hoo_user_story.user_id',
					'from_table_id' => 'hoo_users.id',
					'type' => '',
			);
			
			$userstory = $this->model_name->select_data_by_condition('hoo_users', $condition , 'hoo_users.id as userid,hoo_users.username,hoo_users.profile_image,hoo_user_story.*' , '', '', '', '', $join_str);
			
			if( !empty( $userstory ) ){
				
				header('Content-Type: application/json');
				$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'View Users Story' ,'data' => $userstory);
				echo json_encode($success);
				exit;
				
			}else{
				
				header('Content-Type: application/json');
				$error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'There is no story for this particular user');
				echo json_encode($error);
				exit;
				
			}
	
        } else {
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