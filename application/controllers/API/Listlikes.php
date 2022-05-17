<?php
/*
Author: Rutul Parikh
API Name: ListPackages
Parameter: apiKey
Description: API will list out all packages
*/

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Listlikes extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
			    
		$condition = array('user_id' => $userid);
		
		$likeinfo= $this->model_name->select_data_by_condition('hoo_likes', $condition, 'user_id,media_id,is_liked' , '', '', '', '', array());
		$goldinfo= $this->model_name->select_data_by_condition('hoo_goldstar', $condition, 'user_id,media_id,gold_star' , '', '', '', '', array());

			if( empty( $likeinfo ) ){
			
			header('Content-Type: application/json');
			$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'No likes');
			echo json_encode($error);
			exit;
			
		} else{
			
		    header('Content-Type: application/json');
		    $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Nos likes & Gold','likes' => $likeinfo,'goldstars'=>$goldinfo);
		    echo json_encode($success);
		    exit;
			
		}
		
		
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }		


	}
}