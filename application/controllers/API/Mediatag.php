<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Mediatag extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array(), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
	$userid = $this->input->post('user_id');
	 $id = $this->input->post('id');
	// $mid = $this->input->post('media_id');

		$join_str[0] = array(
			'table' => 'hoo_memory_media',
			'join_table_id' => 'hoo_memory_media.id',
			'from_table_id' => 'hoo_tags.media_id',
			'type' => '',
		);


		$condition = array('hoo_tags.user_id='=>$userid);

		$columns  = 'hoo_tags.media_id,hoo_tags.user_id,hoo_memory_media.media_url';
      
        //  $avail='SUM(hoo_user_points.points)-(hoo_reward.redeemed) as Availiablepoints';

        //  $avail1='SUM(hoo_user_points.points)-(hoo_reward.redeemed)-(hoo_reward.redeemed) As Availiable';

        // $columns= array('details' => $data,
        // 'avaliablepoints' => $avail);

		$groupby = 'hoo_tags.user_id';
	
		$otherinfo = $this->model_name->select_data_by_allcondition('hoo_tags', $condition,$columns,'', 'desc', '', '', $join_str);
   
		/* User Object */
		
		// $join_str[0] = array(
		// 	'table' => 'hoo_user_points',
		// 	'join_table_id' => 'hoo_user_points.user_id',
		// 	'from_table_id' => 'hoo_reward.user_id',
		// 	'type' => '',
		// );
		
		// $condition = array('hoo_reward.id =' => $user_id);
		
		// $columns = 'hoo_reward.id,SUM(hoo_user_points.points)-(hoo_reward.redeemed) as points';
		
		// $userinfo =  $this->model_name->select_data_by_allcondition('hoo_reward', $condition, $columns, 'points', 'desc', '', '', $join_str,'');
		
		$info = array_merge_recursive($otherinfo);

		if( !empty($info) ){
			
		   header('Content-Type: application/json');
		   $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Media Tags List','data' => $info);
		   echo json_encode($success);
		   exit;
		
		}else{
			
		   header('Content-Type: application/json');
		   $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'There Are No Users With Tags');
		   echo json_encode($error);
		   exit;
			
		}
    // }
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}