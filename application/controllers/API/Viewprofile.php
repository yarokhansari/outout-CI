<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Viewprofile extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){

        $userid = $this->input->post('userid');

        $condition = array();
        $columns = "";
        
        $join_str[0] = array(
            'table' => 'hoo_packages',
            'join_table_id' => 'hoo_packages.id',
            'from_table_id' => 'hoo_users.package_id',
            'type' => '',
        );
        
        $condition = array('hoo_users.is_delete' => '0' , 'hoo_packages.is_delete' => '0');
        
        $columns = 'hoo_packages.id,hoo_packages.name,hoo_packages.price';
        
        $packageinfo =  $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
        
        /* User Friends Object */
        
        $condition = array();
        $columns = "";
        
        $join_str[0] = array(
            'table' => 'hoo_friend_request',
            'join_table_id' => 'hoo_friend_request.from_user_id',
            'from_table_id' => 'hoo_users.id',
            'type' => '',
        );
        
        $condition = array('hoo_users.is_delete' => '0' , 'hoo_friend_request.from_user_id' => $userid ,'hoo_friend_request.status' => '1');
        
        $columns = 'COUNT(hoo_friend_request.to_user_id) as friends';
        
        $friendsinfo =  $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
        
        /* User Followers Object */
        
        $condition = array();
        $columns = "";
        
        $join_str[0] = array(
            'table' => 'hoo_friend_request',
            'join_table_id' => 'hoo_friend_request.from_user_id',
            'from_table_id' => 'hoo_users.id',
            'type' => '',
        );
        
        $condition = array('hoo_users.is_delete' => '0' , 'hoo_friend_request.from_user_id' => $userid ,'hoo_friend_request.is_follow' => '1');
        
        $columns = 'COUNT(hoo_friend_request.to_user_id) as followers';
        
        $followersinfo =  $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);

        $condition = "is_delete = '0' AND id = ".$userid."";
					
        $columns = 'id,first_name,last_name,profile_image,dob,city,is_verified,wallet,is_trial_completed';
        
        $basicinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', array());

		foreach( $basicinfo as $basic ){
						
            $info['id'] = $basic['id'];
            $info['first_name'] = $basic['first_name'];
            $info['last_name'] = $basic['last_name'];
            $info['profile_image'] = $basic['profile_image'];
            $info['dob'] = $basic['dob'];
            $info['city'] = $basic['city'];
            $info['is_verified'] = $basic['is_verified'];
            $info['wallet'] = $basic['wallet'];
            $info['banner']=$basic['banner'];
            if( $pointsinfo[0]['points'] != "" ){
                $info['points'] = $pointsinfo[0]['points'];
            }else{
                $info['points'] = "0";
            }
            
            $info['is_trial_completed'] = $basic['is_trial_completed'];
            $info['package_id'] = $packageinfo[0]['id'];
            $info['package_name'] = $packageinfo[0]['name'];
            $info['package_price'] = $packageinfo[0]['price'];
            $info['friends'] = $friendsinfo[0]['friends'];
            $info['followers'] = $followersinfo[0]['followers'];
            
        }

        $rating = $this->model_name->select_data_by_condition('hoo_rating',array('to_user_id'=>$info['id']), 'avg(rating) AS Rating','' , '' ,'', '', array());
        if($rating[0]['Rating']==null)
              $info['Rating'] = 0;
          else
            $info['Rating'] = $rating[0]['Rating'];
        
        if( !empty($info) ){
            
           header('Content-Type: application/json');
           $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'User Dashboard Data','data' => $info);
           echo json_encode($success);
           exit;
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}
}