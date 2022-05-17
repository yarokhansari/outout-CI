<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class UserDetails extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
		$today = date('Y-m-d H:i:s');
	    
		
		/* Check whether free trial is completed or not */
		
		$columns = "DATEDIFF('".$today."', created_at) as days";

		$condition = "is_delete = '0' AND id = ".$userid." AND account_type = '1' AND is_trial_completed = '0'";
		
		$daysinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns , '', '', '', '', array());
		
	
		if( !empty( $daysinfo[0]['days'] ) ){
			
				/* Business Account */
			
				if( $daysinfo[0]['days'] > 30 ){
			
					 header('Content-Type: application/json');
					 $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Your trial period has been completed. Please subscribe with our plans');
					 echo json_encode($error);
					 exit;
				} else {
				
					/* Basic Info */
				
					$condition = "is_delete = '0' AND id = ".$userid."";
					
					$columns = 'id,first_name,last_name,profile_image,dob,city,is_verified,wallet,is_trial_completed,website,biography,account_type,banner';
					
					$basicinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', array());
					
					$days = (int)30 - $daysinfo[0]['days'];
					
					
					
					/* Points Information */
					
					$join_str[0] = array(
						'table' => 'hoo_users',
						'join_table_id' => 'hoo_users.id',
						'from_table_id' => 'hoo_user_points.user_id',
						'type' => '',
					);
				
					$condition = "hoo_users.is_delete = '0' AND hoo_user_points.user_id = ".$userid."";
					
					$columns = 'SUM(hoo_user_points.points) as points';
					
					$pointsinfo = $this->model_name->select_data_by_condition('hoo_user_points', $condition, $columns, '', '', '', '', $join_str);
					
					/* User Package */
					
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
					
					/* User Unread Notification Object */
					
					$condition = array();
					$columns = "";
					
					$join_str[0] = array(
						'table' => 'hoo_notifications',
						'join_table_id' => 'hoo_notifications.user_id',
						'from_table_id' => 'hoo_users.id',
						'type' => '',
					);
					
					$condition = array('hoo_users.is_delete' => '0' , 'hoo_notifications.user_id' => $userid, 'hoo_notifications.is_read' => '0');
					
					$columns = 'COUNT(*) as UnreadNotifications';
					
					$unreadnotification =  $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
					
					/* User Read Notification Object */
					
					$condition = array();
					$columns = "";
					
					$join_str[0] = array(
						'table' => 'hoo_notifications',
						'join_table_id' => 'hoo_notifications.user_id',
						'from_table_id' => 'hoo_users.id',
						'type' => '',
					);
					
					$condition = array('hoo_users.is_delete' => '0' , 'hoo_notifications.user_id' => $userid, 'hoo_notifications.is_read' => '1');
					
					$columns = 'COUNT(*) as ReadNotifications';
					
					$readnotification =  $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
					
					/* User Overall Rating */

					$join_str[0] = array(
						'table' => 'hoo_rating',
						'join_table_id' => 'hoo_rating.to_user_id',
						'from_table_id' => 'hoo_users.id',
						'type' => '',
					);
					
					$condition = array('hoo_users.is_delete' => '0' , 'hoo_rating.to_user_id' => $userid);
					
					$columns = 'SUM(rating) as TotalRatings, COUNT(*) as TotalPersons';
					
					$userrating = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
					
				    $overallrating = (float)$userrating[0]['TotalRatings'] / $userrating[0]['TotalPersons'];
				
					
					$clause = "to_user = $userid AND checkin= '1' AND DATE(created_at) = CURDATE()";

					$checkedin = $this->custom->getcheckindata('hoo_checkin',$clause, 'count(checkin) As Checkin' ,'' , '' ,'', '', array());
					
					foreach( $basicinfo as $basic ){
						
						$info['id'] = $basic['id'];
						$rating = $this->model_name->select_data_by_condition('hoo_rating',array('to_user_id'=>$info['id']), 'avg(rating) AS Rating','' , '' ,'', '', array());
						$info['first_name'] = $basic['first_name'];
						$info['last_name'] = $basic['last_name'];
						$info['profile_image'] = $basic['profile_image'];
						$info['dob'] = $basic['dob'];
						$info['city'] = $basic['city'];
						$info['is_verified'] = $basic['is_verified'];
						$info['wallet'] = $basic['wallet'];
						$info['banner']=$basic['banner'];
						$info['Checkedin']=$checkedin[0]['Checkin'];
						if( $pointsinfo[0]['points'] != "" ){
							$info['points'] = $pointsinfo[0]['points'];
						}else{
							$info['points'] = "0";
						}
						if( $basic['account_type'] == '1' ){
							$info['days'] = $days . ' days are left';	
						}
						if( $basic['website'] != "" ){
							$info['website'] = $basic['website'];
						}else {
							$info['website'] = "";
						}
						if( $basic['biography'] != "" ){
							$info['biography'] = $basic['biography'];
						}else {
							$info['biography'] = "";
						}
						$info['is_trial_completed'] = $basic['is_trial_completed'];
						$info['package_id'] = $packageinfo[0]['id'];
						$info['package_name'] = $packageinfo[0]['name'];
						$info['package_price'] = $packageinfo[0]['price'];
						$info['friends'] = $friendsinfo[0]['friends'];
						$info['followers'] = $followersinfo[0]['followers'];
						$info['unreadNotifications'] = $unreadnotification[0]['UnreadNotifications'];
						$info['readNotifications'] = $readnotification[0]['ReadNotifications'];
						if($rating[0]['Rating']==null)
							  $info['Rating'] = 0;
						  else
							$info['Rating'] = $rating[0]['Rating'];
						
						
					}
					
					if( !empty($info) ){
					   header('Content-Type: application/json');
					   $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'User Dashboard Data','data' => $info);
					   echo json_encode($success);
					   exit;
					
					}else{
						
					   header('Content-Type: application/json');
					   $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'There is no user information');
					   echo json_encode($error);
					   exit;
						
					}
				
			  } 
				
			} else {
					/* Not an business account */
				
					$condition = "is_delete = '0' AND id = ".$userid."";
					
					$columns = 'id,first_name,last_name,profile_image,dob,city,is_verified,wallet,is_trial_completed';
					
					$basicinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', array());
					
					
					/* Points Information */
					
					$join_str[0] = array(
						'table' => 'hoo_users',
						'join_table_id' => 'hoo_users.id',
						'from_table_id' => 'hoo_user_points.user_id',
						'type' => '',
					);
				
					$condition = "hoo_users.is_delete = '0' AND hoo_user_points.user_id = ".$userid."";
					
					$columns = 'SUM(hoo_user_points.points) as points';
					
					$pointsinfo = $this->model_name->select_data_by_condition('hoo_user_points', $condition, $columns, '', '', '', '', $join_str);
					
					/* User Package */
					
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
					
					/* User Unread Notification Object */
					
					$condition = array();
					$columns = "";
					
					$join_str[0] = array(
						'table' => 'hoo_notifications',
						'join_table_id' => 'hoo_notifications.user_id',
						'from_table_id' => 'hoo_users.id',
						'type' => '',
					);
					
					$condition = array('hoo_users.is_delete' => '0' , 'hoo_notifications.user_id' => $userid, 'hoo_notifications.is_read' => '0');
					
					$columns = 'COUNT(*) as UnreadNotifications';
					
					$unreadnotification =  $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
					
					/* User Read Notification Object */
					
					$condition = array();
					$columns = "";
					
					$join_str[0] = array(
						'table' => 'hoo_notifications',
						'join_table_id' => 'hoo_notifications.user_id',
						'from_table_id' => 'hoo_users.id',
						'type' => '',
					);
					
					$condition = array('hoo_users.is_delete' => '0' , 'hoo_notifications.user_id' => $userid, 'hoo_notifications.is_read' => '1');
					
					$columns = 'COUNT(*) as ReadNotifications';
					
					$readnotification =  $this->model_name->select_data_by_condition('hoo_users', $condition, $columns, '', '', '', '', $join_str);
					$clause = "to_user = $userid AND checkin= '1' AND DATE(created_at) = CURDATE()";

					$checkedin = $this->custom->getcheckindata('hoo_checkin',$clause, 'count(checkin) As Checkin' ,'' , '' ,'', '', array());
					
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
						$info['Checkedin']=$checkedin[0]['Checkin'];
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
						$info['unreadNotifications'] = $unreadnotification[0]['UnreadNotifications'];
						$info['readNotifications'] = $readnotification[0]['ReadNotifications'];
						
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
					   $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'There is no user information');
					   echo json_encode($error);
					   exit;
						
					}
				
				
			}
		
	
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}