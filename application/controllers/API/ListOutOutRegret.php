<?php

require(APPPATH . '/libraries/REST_Controller.php');

class ListOutOutRegret extends REST_Controller {

   function index(){
	   
	   $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	   if( !empty($alldevices) ){
		   
		   $userid = $this->input->post('userid');
		   
		   
		   $join_str[0] = array(
				'table' => 'hoo_user_regret',
				'join_table_id' => 'hoo_user_regret.storyid',
				'from_table_id' => 'hoo_user_story.id',
				'type' => '',
		   );
			
		   $condition = array(
				'hoo_user_story.is_delete' => '0',
				'hoo_user_regret.userid' => $userid
		   );
		   
		   $info = $this->model_name->select_data_by_allcondition('hoo_user_story', $condition, 'hoo_user_regret.id,hoo_user_story.id as storyid,hoo_user_story.story' , '', '', '', '', $join_str);
		   
		   if( !empty( $info ) ){
			   
			   header('Content-Type: application/json');
			   $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'OutOut Regret List','data' => $info);
			   echo json_encode($success);
			   exit;
			   
		   } else{
			   
			   header('Content-Type: application/json');
			   $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'There is no outout regret list currently for this user.');
			   echo json_encode($error);
			   exit;
			   
		   }
		   
		   
	   } else {
		   
		   header('Content-Type: application/json');
		   $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		   echo json_encode($error);
		   exit;
	   }

   }
}