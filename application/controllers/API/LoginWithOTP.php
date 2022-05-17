<?php
/* API Name: Login with username and password
   Parameter: intUdId,deviceToken,phone_number
   Description: API will login
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class LoginWithOTP extends REST_Controller {

  function index(){

		
            /** Add Device Before Login */

            /** Check whether this device id and fcm token already exists */

            $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('intUdId' => $this->input->post('deviceId'),'deviceToken' => $this->input->post('fcmToken') ), '*' ,'' , '' ,'', '', array());

            if( !empty($alldevices) ){
				$country_code = $this->input->post('country_code');
				$phone = $this->input->post('phone_number');
				
				$phone = preg_replace("/^\+?{$country_code}/", '',$this->input->post('phone_number'));
				
                $checkLogin = $this->model_name->select_data_by_condition('hoo_users', array('phone_number' => $phone ), '*', '', '', '', '', array());
                if( !empty($checkLogin) ){
                    if( $checkLogin[0]['is_verified'] == '0' ){
                        header('Content-Type: application/json');
                        $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Your account is not yet verified. Please check your inbox/spam folder with your registered email address Or SMS in your registered mobile');
                        echo json_encode($error);
                        exit;

                    }else{

                        $access_token = $this->genRandomToken();
                        $update_array = array(
                            'user_id' => $checkLogin[0]['id'],
                            'isLogin' => '1',
                            'accessToken' => $access_token,
                            'updated_at' => date('Y-m-d h:i:s'),
                        );

                        $this->model_name->update_data($update_array, 'hoo_devices', 'id', $alldevices[0]['id']);

                        /** Get OTP */
                        $otp = RAND(0,9999);
                        $update_user = array(
                            'otp' => $otp,
                            'updated_at' => date('Y-m-d h:i:s'),
                        );

                        if( $this->model_name->update_data($update_user, 'hoo_users', 'id', $checkLogin[0]['id']) ){
                            $userdetails = $this->model_name->select_data_by_condition('hoo_users', array('phone_number' => $phone), '*', '', '', '', '', array());
                            $data = array('userdetails' => $userdetails[0], 'access_token' => $access_token);
                            header('Content-Type: application/json');
                            $error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Please enter OTP to login successfully','data' => $data);
                            echo json_encode($error);
                            exit;

                        }else{
                            header('Content-Type: application/json');
                            $error = array('status' => 'Success', 'errorcode' => '1', 'msg' => 'There is some issue in sending OTP');
                            echo json_encode($error);
                            exit;
                        }

                       

                    }
                    
                }else{
                    header('Content-Type: application/json');
                    $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'No such data exists with this contact number');
                    echo json_encode($error);
                    exit;

                }


            }else{

                $device = array(
                    "intUdId" => $this->input->post('deviceId'),
                    "deviceToken" => $this->input->post('fcmToken'),
                    "deviceType" => '1',
                    'created_at' => date('Y-m-d h:i:s'),
    
                );
    
                $add_device =  $this->model_name->insert_data_getid($device, "hoo_devices");
    
                if( $add_device ){
    
                    /**  Check with phone number */
					
					$country_code = $this->input->post('country_code');
					$phone = $this->input->post('phone_number');
				
					$phone = preg_replace("/^\+?{$country_code}/", '',$this->input->post('phone_number'));
            
                    $checkLogin = $this->model_name->select_data_by_condition('hoo_users', array('phone_number' => $phone), '*', '', '', '', '', array());
                    if( !empty($checkLogin) ){
                        if( $checkLogin[0]['is_verified'] == '0' ){
                            header('Content-Type: application/json');
                            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Your account is not yet verified. Please check your inbox/spam folder with your registered email address Or SMS in your registered mobile');
                            echo json_encode($error);
                            exit;
    
                        }else{
    
                            $access_token = $this->genRandomToken();
                            $update_array = array(
                                'user_id' => $checkLogin[0]['id'],
                                'isLogin' => '1',
                                'accessToken' => $access_token,
                                'updated_at' => date('Y-m-d h:i:s'),
                            );
    
                            $this->model_name->update_data($update_array, 'hoo_devices', 'id', $add_device);
    
                            /** Get OTP */
                            $otp = RAND(0,9999);
                            $update_user = array(
                                'otp' => $otp,
                                'updated_at' => date('Y-m-d h:i:s'),
                            );
    
                            if( $this->model_name->update_data($update_user, 'hoo_users', 'id', $checkLogin[0]['id']) ){
                                $userdetails = $this->model_name->select_data_by_condition('hoo_users', array('phone_number' => $phone), '*', '', '', '', '', array());
                                $data = array('userdetails' => $userdetails[0], 'access_token' => $access_token);
                                header('Content-Type: application/json');
                                $error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Please enter OTP to login successfully','data' => $data);
                                echo json_encode($error);
                                exit;
    
                            }else{
                                header('Content-Type: application/json');
                                $error = array('status' => 'Success', 'errorcode' => '1', 'msg' => 'There is some issue in sending OTP');
                                echo json_encode($error);
                                exit;
                            }
    
                           
    
                        }
                        
                    }else{
                        header('Content-Type: application/json');
                        $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'No such data exists with this contact number');
                        echo json_encode($error);
                        exit;
    
                    }
        
    
                }else{
        
                    header('Content-Type: application/json');
                    $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Device configurations are not proper');
                    echo json_encode($error);
                    exit;
                    
                }


            }
            
      }

    }
?>