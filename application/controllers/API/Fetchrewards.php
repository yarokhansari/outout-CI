<?php
/*
Author: Ismail
API Name: ListPackages
Parameter: apiKey
Description: API will list out all packages
*/

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Fetchrewards extends REST_Controller {
	
	function index() {

        $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
			$id = $this->input->post('userid');
			$packages = $this->model_name->select_data_by_condition('hoo_rewardnew',array(), 'id,name,level,points' ,'' , '' ,'', '', array());
			$total_points=$this->model_name->select_data_by_condition('hoo_allpoints',array('user_id'=>$id), 'totalpoints' ,'' , '' ,'', '', array());
			$points=$this->model_name->select_data_by_condition('hoo_user_points',array('user_id'=>$id), 'SUM(points) AS points' ,'' , '' ,'', '', array());
			$redeem=$this->model_name->select_data_by_condition('hoo_reward',array('user_id'=>$id), 'SUM(redeemed) AS points' ,'' , '' ,'', '', array());
		 	$count = 0;
		  
		  foreach( $packages as $package ){
			  
			  $info[$count]['id'] = $package['id'];
			  $info[$count]['name'] = $package['name'];
			  $info[$count]['level'] = $package['level'];
			  $info[$count]['points'] = $package['points'];
			//   $info[$count]['sponsor'] = $package['sponsor'];
			//   $info[$count]['request'] = $package['request'];
			//   $info[$count]['message'] = $package['message'];
			  
			  $packageinfo= $info;
			  
			  $count++;
			  
		  }
		  
		  header('Content-Type: application/json');
		  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Rewards data','Details' => $packageinfo,'Totalpoints'=>$total_points,'Tillnow'=>$points,'Redeemed'=>$redeem);
		  echo json_encode($success);
		  exit;	  
	}
    else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

}
}