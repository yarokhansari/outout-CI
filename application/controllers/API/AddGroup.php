<?php

require(APPPATH . '/libraries/REST_Controller.php');

class AddGroup extends REST_Controller {

  function index(){

		 $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
         if( !empty($alldevices) ){
			 
			    $data['name'] = $this->input->post('name');
				$data['userid'] = $this->input->post('userid');
				
				$nameExists = 0;
				
				$info = $this->model_name->select_data_by_condition("hoo_chat_group", array(), '*', '', '', '', '', array());
				foreach( $info as $key => $info_ ){
					
					if( $info_['name'] == $data['name'] ){
						$nameExists = 1;
					}
					
				}
				
				if( $nameExists == 1 ){
					
					header('Content-Type: application/json');
					$error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'This name already exists. Please create group with another name');
					echo json_encode($error);
					exit;
					
				}else{
					
					$image_base64 = base64_decode($this->input->post('groupphoto'));
					$file = $this->config->item('upload_group_path') . uniqid() . '.jpg';
					file_put_contents($file, $image_base64);
					$image = base_url() .  $file;
					
					
					
					$submitgroupData = array(
						  "name" => $data['name'],
						  "groupphoto" => $image,
						  "userid" => $data['userid'],
						  "created_at" => date('Y-m-d h:i:s'),
					);

					$submit_group = $this->model_name->insert_data_getid($submitgroupData, "hoo_chat_group");
					
					
					
					if( $submit_group ){
						
					   $adduserinGroup = array(
							  "userid" => $data['userid'],
							  "groupid" => $submit_group,
							  "adduserid" => $data['userid'],
							  "type" => '1',
							  "created_at" => date('Y-m-d h:i:s'),
					   );
					   $submit_user_group = $this->model_name->insert_data_getid($adduserinGroup, "hoo_users_chat_group");
						
					   header('Content-Type: application/json');
					   $groups = $this->model_name->select_data_by_condition("hoo_chat_group", array('userid' => $data['userid'],'id' => $submit_group), '*', '', '', '', '', array());
					   $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Group created successfully','data' => $groups);
					   echo json_encode($success);
					   exit;
						
					}else{
						
					   header('Content-Type: application/json');
					   $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'There is error in creating group');
					   echo json_encode($error);
					   exit;
						
						
					}
					
				}

			 
		 }else{
			 
			header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
            echo json_encode($error);
            exit;
			 
		 }

		
  }

}