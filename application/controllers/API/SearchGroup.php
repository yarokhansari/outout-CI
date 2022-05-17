<?php

require(APPPATH . '/libraries/REST_Controller.php');

class SearchGroup extends REST_Controller {

  function index(){

		 $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
         if( !empty($alldevices) ){
			 
			    $data['name'] = $this->input->post('name');
				
				$condition = "LOWER(NAME) LIKE LOWER('%".$data['name']."%') OR UPPER(NAME) LIKE UPPER('%".$data['name']."%') AND `is_delete` = '0'";
				
				$info = $this->model_name->select_data_by_condition("hoo_chat_group", $condition, '*', '', '', '', '', array());
				
				if( !empty( $info ) ){
					
					header('Content-Type: application/json');
					$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Group exists','data' => $info);
					echo json_encode($success);
					exit;
					
				}else{
					header('Content-Type: application/json');
					$error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'There is no such group exists');
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