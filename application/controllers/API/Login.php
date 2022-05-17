<?php
/* API Name: Login with username and password
   Parameter: intUdId,deviceToken,username,password
   Description: API will login
 */


require(APPPATH . '/libraries/REST_Controller.php');

class Login extends REST_Controller {

  function index(){

        /** Check whether this device id and fcm token already exists */

        $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('intUdId' => $this->input->post('deviceId'),'deviceToken' => $this->input->post('fcmToken') ), '*' ,'' , '' ,'', '', array());
			
		/* Retrieve user details based on device */
		
		$checkLogin = $this->model_name->select_data_by_condition('hoo_users', array('username' => $this->input->post('username') , 'is_delete' => '0'), '*', '', '', '', '', array());
		
	
		if( !empty( $checkLogin ) ){
			
			$devicelist = $this->model_name->select_data_by_condition('hoo_devices',array('user_id' => $checkLogin[0]['id'], 'isLogin' => '1'), '*' ,'' , '' ,'', '', array());
					
			foreach( $devicelist as $device ){
				
				/*$update_array = array(
					'isLogin' => '0',
					'updated_at' => date('Y-m-d h:i:s'),
				);
				$this->model_name->update_data($update_array, 'hoo_devices', 'id', $device['id']);*/
				
				$this->model_name->delete_data('hoo_devices','id', $device['id']);
				
			}
			
			if( $checkLogin[0]['is_verified'] == '0' ){
				header('Content-Type: application/json');
				$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'Your account is not yet verified. Please check your inbox/spam folder with your registered email address Or SMS in your registered mobile');
				echo json_encode($error);
				exit;

			}else if( $checkLogin[0]['is_delete'] == '1' ){
				header('Content-Type: application/json');
				$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'No such data exists with this username');
				echo json_encode($error);
				exit;

			}else{
				
				$dbPassword = $checkLogin[0]['password'];
				
				if (password_verify($this->input->post('password'), $dbPassword)){
					
					 
					 $device = array(
						"intUdId" => $this->input->post('deviceId'),
						"deviceToken" => $this->input->post('fcmToken'),
						"deviceType" => '1',
						"user_id" => $checkLogin[0]['id'],
						'created_at' => date('Y-m-d h:i:s'),
			  
					  );
  
					  $add_device =  $this->model_name->insert_data_getid($device, "hoo_devices");
					  
					  if( $add_device ){
						  
						  $access_token = $this->genRandomToken();
						  $update_array = array(
							  'isLogin' => '1',
							  'accessToken' => $access_token,
							  'updated_at' => date('Y-m-d h:i:s'),
						  );


						  $this->model_name->update_data($update_array, 'hoo_devices', 'id', $add_device);
						  
						  $userdetails = $this->model_name->select_data_by_condition('hoo_users', array('id' => $checkLogin[0]['id']), '*', '', '', '', '', array());
						 
						  if($userdetails[0]['account_type']==1)
						  {
					$menu = $this->model_name->select_data_by_condition('hoo_food_menu',array('userid' => $userdetails[0]['id']), '*' ,'' , '' ,'', '', array());
					if($menu==null){
						$userdetails[0]['menu_added']=0;
					}
					else{
						$userdetails[0]['menu_added']=1;
					}
					
						  }
						 
						  $data = array('userdetails' => $userdetails[0],'access_token' => $access_token);
						header('Content-Type: application/json');
						
						  $success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'You are successfully logged in','data' => $data);
						  echo json_encode($success);
						  exit;
					  }else{
						  
						   header('Content-Type: application/json');
						   $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Device configurations are not proper');
						   echo json_encode($error);
						   exit;
						  
					  }
					   
					
				}else{
					
					header('Content-Type: application/json');
					$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'Wrong credentials');
					echo json_encode($error);
					exit;
					
				}
				
			}
			
		}else{
			
			 header('Content-Type: application/json');
			 $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'Username is incorrect');
			 echo json_encode($error);
			 exit;
			
		}
	   
  }

}

?>