<?php
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

require_once "RtcTokenBuilder.php";

class GetAgoraToken extends REST_Controller {

  function index(){
	  
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $userid = $this->input->post('userid');
		  
		  $appID = '92841c5e791c4ebc8e34eb16a13ca5f3';
		  $appCertificate = 'a37afc0c7e144699b36e475c5f106d57';
		
		  $channelName = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 10)), 0, 10);
	  
		  $token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, 0 , 1 , 0);
		  
		  $tokendata = array(
			'channelName' => $channelName,
			'agoraToken' => $token,
			'userid' => $userid
		  );
		
		  $updatedata = array(
				'channelName' => $channelName,
				'agoraToken' => $token,
				'updated_at' => date('Y-m-d h:i:s')
		  );
			
		  $updateToken = $this->model_name->update_data($updatedata, "hoo_devices",'id', $alldevices[0]['id']);
		  if( $updateToken ){
				
		   header('Content-Type: application/json');
		   $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Agora Token is updated successfully', 'data' => $tokendata);
		   echo json_encode($success);
		   exit;
			
		 }else{
			
		   header('Content-Type: application/json');
		   $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Agora Token has not been updated');
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
