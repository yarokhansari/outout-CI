<?php
/* API Name: Login with username and password
   Parameter: userid, accesstoken, newpassord, confirmnewpassword, apikey
   Description: API will login
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class ChangePassword extends REST_Controller {

  function index(){


            /** Add Device Before Login */
           
            $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
            if( !empty($alldevices) ){

            /**  Check with userid */
            $userid = $this->input->post('userid');

            $checkLogin = $this->model_name->select_data_by_condition('hoo_users', array('id' => $this->input->post('userid')), '*', '', '', '', '', array());
            if( !empty($checkLogin) ){
                if( $checkLogin[0]['is_verified'] == '0' ){
                    header('Content-Type: application/json');
                    $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Your account is not yet verified. Please check your inbox/spam folder with your registered email address Or SMS in your registered mobile');
                    echo json_encode($error);
                    exit;

                }else{

                    $newpassword = $this->input->post('newpassword');
                    $confirmnewpassword = $this->input->post('confirmnewpassword');

                    if( $newpassword != $confirmnewpassword ){

                        header('Content-Type: application/json');
                        $error = array('status' => 'Failure', 'errorcode' => '1', 'msg' => 'Password and New Password are not matching');
                        echo json_encode($error);
                        exit;

                    }else{

                        $update_user = array(
                            'password' => password_hash($this->input->post('newpassword'), PASSWORD_BCRYPT),
                            'updated_at' => date('Y-m-d h:i:s'),
                        );

                        if( $this->model_name->update_data($update_user, 'hoo_users', 'id', $userid) ){
                            header('Content-Type: application/json');
                            $error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Password is changed successfully');
                            echo json_encode($error);
                            exit;
    
                        }else{
                            header('Content-Type: application/json');
                            $error = array('status' => 'Success', 'errorcode' => '1', 'msg' => 'There is some issue in updating password');
                            echo json_encode($error);
                            exit;
                        }

                    }
       

                }
                
            }else{
                header('Content-Type: application/json');
                $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'No such user exists');
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
?>