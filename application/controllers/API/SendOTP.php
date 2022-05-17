<?php
/* API Name: Login with username and password
   Parameter: intUdId,deviceToken,phone_number
   Description: API will login
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class SendOTP extends REST_Controller {

  function index(){


        /**  Check with phone number */
        $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
        if( !empty($alldevices) ){
    
        $checkLogin = $this->model_name->select_data_by_condition('hoo_users', array('phone_number' => $this->input->post('phone_number')), '*', '', '', '', '', array());
        if( !empty($checkLogin) ){
            if( $checkLogin[0]['is_verified'] == '0' ){
                header('Content-Type: application/json');
                $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Your account is not yet verified. Please check your inbox/spam folder with your registered email address Or SMS in your registered mobile');
                echo json_encode($error);
                exit;

            }else if( $checkLogin[0]['is_delete'] == '1' ){
                header('Content-Type: application/json');
                $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'No such data exists with this contact number');
                echo json_encode($error);
                exit;

            }else{

                /** Generate OTP and store it in users table */

                $otp = RAND(0,9999);
                $update_user = array(
                    'otp' => $otp,
                    'updated_at' => date('Y-m-d h:i:s'),
                );
                if( $this->model_name->update_data($update_user, 'hoo_users', 'id', $checkLogin[0]['id']) ){

                    $fullName = $checkLogin[0]['first_name'] . " " . $checkLogin[0]['last_name'];
                    $email = $checkLogin[0]['email'];
                    /** Send otp in email */

                    $url = base_url();
                    $emailBody = file_get_contents(base_url() .'email-templates/send-otp.html');
                    $emailBody = str_replace('<<BASEURL>>', $url, $emailBody); // Dynamic variable
                    $emailBody = str_replace('<<USERNAME>>', $fullName, $emailBody); // Dynamic variable
                    $emailBody = str_replace('<<OTP>>', $otp, $emailBody); // Dynamic variable
                    $subject = "OutOut OTP";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: <info@outout.app>' . "\r\n";

                    $sendmail  = mail($email,$subject,$emailBody,$headers);

                    if( $sendmail == 1 ){
                        header('Content-Type: application/json');
                        $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'We have sent you an OTP on your registered email address. Please check your inbox for account verification email');
                        echo json_encode($success);
                        exit;

                    }else{
                        header('Content-Type: application/json');
                        $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'There is error in sending an email');
                        echo json_encode($error);
                        exit;

                    }

                }else{
                    header('Content-Type: application/json');
                    $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'User data is not updated');
                    echo json_encode($error);
                    exit;
                }
                

            }
            
        }else{
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Phone number is incorrect');
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