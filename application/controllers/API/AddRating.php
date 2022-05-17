<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AddRating extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
	  
		$from_user_id = $this->input->post('from_user_id');
		$to_user_id = $this->input->post('to_user_id');
		$rating = $this->input->post('rating');
	 	$comments = $this->input->post('comments');
		$data = $this->model_name->select_data_by_condition('hoo_rating',array('from_user_id' => $from_user_id ), '*' ,'' , '' ,'', '', array());
		// if($data==null)
		// {
			$addrating = array(
				'from_user_id' => $from_user_id,
				'to_user_id' => $to_user_id,
				'rating' => $rating,
				'comments' => $comments,
				'created_at' => date('Y-m-d H:i:s'),
			);
			
			$createrating = $this->model_name->insert_data_getid($addrating, "hoo_rating");
		
		header('Content-Type: application/json');
		$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Thanks for your valuable feedback');
		echo json_encode($success);
		exit;
		
		// }
		// else{
		// 	header('Content-Type: application/json');
		// 	$error = array('status' => 'failure', 'errorcode' => '3', 'msg' => 'Already Added Feedback');
		// 	echo json_encode($error);
		// }
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}