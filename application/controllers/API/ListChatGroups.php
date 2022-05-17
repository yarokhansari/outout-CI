<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class ListChatGroups extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $count = 0;
		  
		  $userid = $this->input->post('userid');
		//   $adduserid = $this->input->post('adduserid');

		// 
		//  
		  $condition = "userid = $userid or adduserid=$userid";
		  $userchats = $this->model_name->select_data_by_condition('hoo_users_chat_group',$condition, '*' , '', '', '', '', array());
	
		  
		  if( !empty( $userchats ) ){
			  
				  /* Conditions for individuals chat */
				  
				  $condition = "hoo_users_chat_group.userid= $userid  AND hoo_users_chat_group.is_delete = '0' AND hoo_users_chat_group.type = '0'";
				  
				  $usergroups = $this->model_name->select_data_by_condition('hoo_users_chat_group', $condition , '*' , '', '', '', '', array());
				  
				  if( !empty( $usergroups ) ){
					  
					  foreach( $usergroups as $usergroup ){
						  
						  $condition = array('type' => '1','is_delete' => '0','id' => $usergroup['adduserid']);

						  $userstory = $this->model_name->select_data_by_condition('hoo_user_story', $condition , '*' , '', '', '', '', array());
						  
						  $users = $this->model_name->select_data_by_condition('hoo_users', array('id' => $usergroup['adduserid'],'is_delete' => '0') , '*' , '', '', '', '', array());
						  
						  $grouplist[$count]['chatid'] = $users[0]['id'];
						  $grouplist[$count]['chatname'] = $users[0]['first_name'] . " " . $users[0]['last_name'];
						  $grouplist[$count]['chatImage'] = $users[0]['profile_image'];
						  $grouplist[$count]['chatType'] = 'Individual';
						  $grouplist[$count]['members'] = 0;
						  $grouplist[$count]['memberlist'] = array();
						  if( !empty( $userstory ) ){
							  $grouplist[$count]['story'] = '1';
						  }else{
							  $grouplist[$count]['story'] = '0';
						  }
						  $individual['chatData'] = $grouplist;
						  $count++;
						  
						  
					  }
					  
				  }
				  
			  /* groupname*/
			 
			  


				 /* */
			
				  /* Conditions for group chat */
				  $groupinfo = $this->model_name->select_data_by_condition('hoo_users_chat_group', array('is_delete' => '0','adduserid'=>$userid ) , '*' , '', '', '', '', array());

				 
				  $count = 0;
				  
				  if( !empty( $groupinfo) ){
					  
					  foreach( $groupinfo as $group){
	
						  
						  $groupid = $group['groupid'];
						//   $groupid1 = $group['id'];
						  $groupdetails = $this->model_name->select_data_by_condition('hoo_chat_group', array('is_delete' => '0','id'=>$group['groupid']) , '*' , '', '', '', '', array());
						  
						  $columns = array('hoo_users.id', 'hoo_users.username','hoo_users.first_name', 'hoo_users.last_name', 'hoo_users.profile_image', 'hoo_users.email');
							
						  $condition = array('hoo_users_chat_group.type' => '1','hoo_users_chat_group.groupid' => $groupid,'hoo_users_chat_group.is_delete' => '0');

						  $join_str[0] = array(
								'table' => 'hoo_users',
								'join_table_id' => 'hoo_users.id',
								'from_table_id' => 'hoo_users_chat_group.adduserid',
								'type' => '',
						  );
						
						  $usergroups = $this->model_name->select_data_by_condition('hoo_users_chat_group', $condition , $columns , '', '', '', '', $join_str);
						  
						  if( !empty( $usergroups) ){
							   $grouplists[$count]['chatid'] = $group['groupid'];
							   $grouplists[$count]['chatname'] =  $groupdetails[0]['name'];
							   $grouplists[$count]['chatimage'] = $groupdetails[0]['groupphoto'];
							//    if( $groupdetails['groupphoto'] != ""  ){
							// 	   $grouplists[$count]['chatImage'] = $groupdetails[0]['groupphoto'];
							//    }else{
							// 	   $grouplists[$count]['chatImage'] = "";
							//    }
							   
							   $grouplists[$count]['chatType'] = 'Group';
							   $grouplists[$count]['members'] = count( $usergroups );
							   $grouplists[$count]['memberlist'] = $usergroups;
							   $groupsdata['chatData'] = $grouplists;
							   $count++;
						  }
						  
					  }
					}
				  
				//   
				

				// 
				  if( !empty( $individual ) && !empty( $groupsdata ) ){
					  $result = array_merge_recursive($individual,$groupsdata);
				  }else if( !empty( $individual ) ){
					  $result = $individual;
				  }else if( !empty( $groupsdata ) ){
					  $result = $groupsdata;
				  }else {
					  $result = array();
				  }
			 
				  header('Content-Type: application/json');
				  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Groups and Users List' ,'data' => $result);
				//   $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Groups and Users List' ,'data' => $grouplists);
				  echo json_encode($success);
				  exit;
			  
				  
		  }else{
			  
			  header('Content-Type: application/json');
			  $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'There are no chat groups for this user');
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