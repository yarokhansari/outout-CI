<?php

require(APPPATH . '/libraries/REST_Controller.php');

class EditGroup extends REST_Controller {

   function index(){
	
		$alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
        if( !empty($alldevices) ){
			
			$data['userid'] = $this->input->post('userid');
			$data['id'] = $this->input->post('id');
			$data['name'] = $this->input->post('name');
			
			$groupinfo = $this->model_name->select_data_by_condition("hoo_chat_group", array('userid' => $data['userid'],'id' => $data['id'],'is_delete' => '0'), '*', '', '', '', '', array());
			
			if( $this->input->post('groupphoto') != ""  ){
				
				$image_base64 = base64_decode($this->input->post('groupphoto'));
				$file = $this->config->item('upload_group_path') . uniqid() . '.jpg';
				file_put_contents($file, $image_base64);
				$image = base_url() .  $file;
				
				$updategroupData = array(
					  "name" => $groupinfo[0]['name'],
					  "groupphoto" => $image,
					  "updated_at" => date('Y-m-d h:i:s'),
				);
				
			}else{
				
				if( $this->input->post('name') != "" ){
					
					$updategroupData = array(
						  "name" => $data['name'],
						  "groupphoto" => $groupinfo[0]['groupphoto'],
						  "updated_at" => date('Y-m-d h:i:s'),
					);
					
				}else{
					
					$updategroupData = array(
						  "name" => $groupinfo[0]['name'],
						  "groupphoto" => $groupinfo[0]['groupphoto'],
						  "updated_at" => date('Y-m-d h:i:s'),
					);
					
					
				}
			
			}

		
			$update_group = $this->model_name->update_data($updategroupData, "hoo_chat_group",'id', $data['id']);
			if( $update_group ){
				
			   header('Content-Type: application/json');
			   $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Group updated successfully');
			   echo json_encode($success);
			   exit;
				
			}else{
				
			   header('Content-Type: application/json');
			   $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'There is error in creating group');
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