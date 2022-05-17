<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Location extends REST_Controller {

  function index(){

		 $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
         if( !empty($alldevices) ){
			 
			   $place = $this->input->post('place'); 
			   if ($place==null)
			   {
				   $condition=array('is_delete'=>'0','account_type'=>'1');
			   }
			   else{

				$condition = array('is_delete' => '0', 'account_type' => '1' , 'city' => $place);
			   }
			   
			   
			   $info = $this->model_name->select_data_by_condition('hoo_users', $condition, '*', 'id', 'desc', '', '', array());
			   
			   if(!empty($info)) {
		  
					$response = array(
						'status' => 'success', 
						'errorcode' => '0', 
						'msg' => 'List Business Accounts',
						'data' => $info
					);
				}
				else {
					$response = array(
						'status' => 'failure', 
						'errorcode' => '1',
						'msg' => 'No account found.'
					);
				}

			   

			  header('Content-Type: application/json');
			  echo json_encode($response);
			  exit;
			   
		 }else{
			 
			header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
            echo json_encode($error);
            exit;
			 
		 }

		
  }

}