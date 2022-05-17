<?php
/*
Author: Yogen Jajal
API Name: Category Listing
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class BusinessCategory extends REST_Controller {
    function index() {
        $response = array();

        $categories = $this->model_name->select_data_by_condition('hoo_category', array('status' => '0', 'is_delete' => '0'), '*', '', '', '', '', array()); //status - 0 means enable

        $count = 0;

        $string;
        
        foreach( $categories as $cat ){
            
            $string=$string.$cat['name'].",";
            $packageinfo= $info;
            $count++;
            
        }

        if(!empty($categories)) {
			
            $response = array(
                'status' => 'Success', 
                'errorcode' => '0', 
                'msg' => 'Business Category',
                'data' => $categories,
            );
        }
        else {
            $response = array(
                'status' => 'Failed', 
                'errorcode' => '1',
                'msg' => 'No categories found.'
            );
        }
  
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    }
}

?>