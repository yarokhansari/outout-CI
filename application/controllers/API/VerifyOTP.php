<?php
/* API Name: Login with username and password
   Parameter: intUdId,deviceToken,phone_number
   Description: API will login
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class VerifyOTP extends REST_Controller {

  function index(){


                /**  Check with phone number */
        $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
        if( !empty($alldevices) ){
    
        $checkOTP = $this->model_name->select_data_by_condition('hoo_users', array('otp' => $this->input->post('otp')), '*', '', '', '', '', array());
        if( !empty($checkOTP) ){
          
                header('Content-Type: application/json');
                $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'OTP verified successfully');
                echo json_encode($success);
                exit;
      
        }else{
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Wrong OTP entered. Please re-enter OTP again');
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

?>