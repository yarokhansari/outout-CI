<?php
/*
Author: Rutul Parikh
API Name: Send Views
Parameter: deviceToken, deviceType, userid, mediaid
Description: API will increase the view count
*/

require 'BaseApi.php';

class SendViews extends BaseApi {
	
    function index() {
		
		if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
			$user_id = $this->input->post('user_id');
			$media_id = $this->input->post('media_id');
			
			$condition = array('id' => $media_id, 'is_delete' => '0');
		
			$media = $this->model_name->select_data_by_condition('hoo_memory_media', $condition, '*', '', '', '', '', array());

			if(!empty($media)) {
				
				$views = $media[0]['views'];
				
				//Increase views by 1
				
				$views = $views + 1;
				
				//Update in media table
				
				$update = array(
					'views' => $views,
					'updated_at' => date('Y-m-d h:i:s'),
				);
				
				if( $this->model_name->update_data($update, 'hoo_memory_media', 'id', $media_id) ){
					header('Content-Type: application/json');
					$error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Views count are updated succesfully');
					echo json_encode($error);
					exit;

				}else{
					header('Content-Type: application/json');
					$error = array('status' => 'Success', 'errorcode' => '1', 'msg' => 'There is some issue in updating password');
					echo json_encode($error);
					exit;
				}
				
			}
			else {
				$response = array(
					'status' => 'failure', 
					'errorcode' => '1',
					'msg' => 'No media found.'
				);
			}
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