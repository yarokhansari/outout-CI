<?php
/* API Name: Validate
   Parameter: Phone,Email,Username
   Description: API will Validate 
 */

require(APPPATH . '/libraries/REST_Controller.php');

class Validate extends REST_Controller {

  function index(){

      $emailExists = 0;
      $phoneExists = 0;
      $usernameExists = 0;

          $data['email'] = $this->input->post('email');
          $data['phone_number'] = $this->input->post('phone_number');
          $data['username'] = $this->input->post('username');
          $info = $this->model_name->select_data_by_condition("hoo_users", array(), '*', '', '', '', '', array());
          foreach ($info as $key => $info_) {
              if($info_['email'] == $data['email']) {
                $emailExists = 1;
              }
              if($info_['phone_number'] == $data['phone_number']) {
                $phoneExists = 1;
              }
              if($info_['username'] == $data['username']) {
                $usernameExists = 1;
              }
          }

          /** Email already exists in database  */

          if( $emailExists == 1 ){
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'This email already exists. Please register using another email address');
            echo json_encode($error);
            exit;
          }else if( $phoneExists == 1 ){
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'This phone number already exists. Please register using another phone number');
            echo json_encode($error);
            exit;
          }else if( $usernameExists == 1 ){
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'This username already exists. Please register using another username');
            echo json_encode($error);
            exit;
          }else
          {
               header('Content-Type: application/json');
                      $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Validated user data not exists');
                      echo json_encode($success);
                      exit;
          }

        


          }
       
  }

?>