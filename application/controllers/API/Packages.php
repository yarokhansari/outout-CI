<?php
/*
Author: Yogen Jajal
API Name: Packages Listing
Parameter: deviceToken, deviceType, userid
Description: API will list out all packages.
*/

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class Packages extends REST_Controller {
    function index() {
        $response = array();

        $packages = $this->model_name->select_data_by_condition('hoo_packages', array('is_delete' => '0'), 'id,name,price,days,duration,inapppurchase_key', '', '', '', '', array());

        if(!empty($packages)) {
            $response = array(
                'status' => 'Success', 
                'errorcode' => '0', 
                'msg' => 'Packages',
                'data' => $packages
            );
        }
        else {
            $response = array(
                'status' => 'Failed', 
                'errorcode' => '1',
                'msg' => 'No packages found.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    }
}

?>