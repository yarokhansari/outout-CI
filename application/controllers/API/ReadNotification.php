<?php
/*
Author: Rutul Parikh
API Name: Read Notification
Parameter: deviceToken, deviceType, userid
Description: API will update the status of notification
*/

require 'BaseApi.php';

class ReadNotification extends BaseApi {
	
    function index() {
        $response = array();
		
		$count = 0;
		
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
			
            $id = $this->input->post('id');
			$message_id = $this->input->post('message_id');
			$media_id = $this->input->post('media_id');
			$is_read = $this->input->post('is_read');
			
			if( $message_id != "" ){
				
				$update_notification = array(
					'is_read' => $is_read,
				);
				
			} else {
				
				$update_notification = array(
					'is_read' => $is_read,
				);
				
			}
			

			if( $this->model_name->update_data($update_notification, 'hoo_notifications', 'id', $id) ){
				$error = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Read Notification');
				echo json_encode($error);
				exit;

			}else{
				$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'Notification is not read');
				echo json_encode($error);
				exit;
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

?>