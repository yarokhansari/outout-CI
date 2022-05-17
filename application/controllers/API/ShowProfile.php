<?php
/*
Author: Yogen Jajal
API Name: Show Profile
Parameter: deviceToken, userid
Description: API will list out all categories.
*/

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class ShowProfile extends REST_Controller {
    function index() {
        $response = array();
        $where = array('accessToken' => $this->input->post('accessToken'));
        $devices = $this->model_name->select_data_by_condition('hoo_devices', $where, '*' ,'' , '' ,'', '', array());
        if(!empty($devices)) {
            $user = $this->model_name->select_data_by_condition('hoo_users', array('id' => $this->input->post('userid')), '*', '', '', '', '', array());

            if(!empty($user)) {
                $response = array(
                    'status' => 'Success', 
                    'errorcode' => '0', 
                    'msg' => 'Show Profile',
                    'data' => $user
                );
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'No user found.'
                );
            }
        }
        else {
            $response = array(
                'status' => 'Failed', 
                'errorcode' => '2',
                'msg' => 'Access Token is incorrect.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

?>