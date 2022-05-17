<?php
/* API Name: Login with username and password
   Parameter: intUdId,deviceToken,userid
   Description: API will logout from app
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class LogOut extends REST_Controller {

  function index(){


        /** Check Device Before Login */
      
        $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken'), 'isLogin' => '1' ), '*' ,'' , '' ,'', '', array());
        if( !empty($alldevices) ){

                /**  Check with phone number */
                $userid = $this->input->post('userid');
                $logindata = $this->model_name->select_data_by_condition('hoo_users', array('id' => $userid), '*', '', '', '', '', array());
                if( !empty($logindata) ){
                    
                    /** Logout and update status in devices table */
					/*$access_token = $this->genRandomToken();
                    $update_array = array(
                        'isLogin' => '0',
                        'accessToken' => $access_token,
                        'updated_at' => date('Y-m-d h:i:s'),
                    );*/

                    /*if ($this->model_name->update_data($update_array, 'hoo_devices', 'user_id', $userid)) {*/
					if($this->model_name->delete_data('hoo_devices','id', $alldevices[0]['id'])){
                        header('Content-Type: application/json');
                        $error = array('status' => 'success', 'errorcode' => '0', 'msg' => 'You are successfully logged out from OutOut app');
                        echo json_encode($error);
                        exit;
                    } else {
                        header('Content-Type: application/json');
                        $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'You are not successfully logged out from OutOut app');
                        echo json_encode($error);
                        exit;
                    }
              
                }else{
                    header('Content-Type: application/json');
                    $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Username is incorrect');
                    echo json_encode($error);
                    exit;


                }
        }else{
            header('Content-Type: application/json');
            $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access token is incorrect');
            echo json_encode($error);
            exit;
        }
   
  }

}

?>