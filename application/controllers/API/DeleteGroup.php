<?php

require(APPPATH . '/libraries/REST_Controller.php');

class DeleteGroup extends REST_Controller {

   function index(){
	
		$alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
        if( !empty($alldevices) ){
			
			$data['groupid'] = $this->input->post('groupid');
			
			$updategroupData = array(
			  "is_delete" => '1',
			  "updated_at" => date('Y-m-d h:i:s'),
			);
			$update_group = $this->model_name->update_data($updategroupData, "hoo_chat_group",'id', $data['groupid']);
			if( $update_group ){
				
				$updategroupuserData = array(
				  "is_delete" => '1',
				  "updated_at" => date('Y-m-d h:i:s'),
				);
				$update_group_users = $this->model_name->update_data($updategroupuserData, "hoo_users_chat_group",'groupid', $data['groupid']);
				
				if( $update_group_users ){
					
				   header('Content-Type: application/json');
				   $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Group deleted successfully');
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