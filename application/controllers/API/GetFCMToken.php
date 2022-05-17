<?php
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetFCMToken extends REST_Controller {
	
	function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  
	  if( !empty($alldevices) ){

		 $userid = $this->input->post('userid');
		 $fcmToken = $this->input->post('fcmtoken');
		 
		 if( $userid != "" ){
			 
			 $data = array(
				"fcmtoken"=> $fcmToken,
				"updated_at" => date('Y-m-d h:i:s'),
			);

			if ($this->model_name->update_data($data, 'hoo_devices', 'id', $alldevices[0]['id'])) {
				
				 header('Content-Type: application/json');
			     $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'FCM token updated successfully' );
			     echo json_encode($success);
			     exit;
			}else{
				
				  header('Content-Type: application/json');
				  $error = array('status' => 'failed', 'errorcode' => '2', 'msg' => 'FCM token is not updated');
				  echo json_encode($error);
				  exit;
				
			}
			 
			 
			 
		 }else{
				
		   header('Content-Type: application/json');
		   $error = array('status' => 'failed', 'errorcode' => '2', 'msg' => 'Please provide userid');
		   echo json_encode($error);
		   exit;
		}

	  }else{
		  
		  header('Content-Type: application/json');
		  $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		  echo json_encode($error);
		  exit;
		  
	  }
        
    }

}