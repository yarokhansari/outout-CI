<?php
/*
Author:Yarokh Ansari
API Name: Category Listing
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class BusinessKey extends REST_Controller {
    function index() {
        $response = array();
        $condition="place_keyword!='0' AND status = '0'";
        $categories = $this->model_name->select_data_by_condition('hoo_category', $condition, '*', '', '', '', '', array()); //status - 0 means enable
        $a=array();
        
        foreach($categories as $key => $name)
        {
            
                $a[$key]['name']=$categories[$key]['name'];
                $a[$key]['key'] = $categories[$key]['place_keyword'];            
        }

        if(!empty($categories)) {
			
            $response = array(
                'status' => 'Success', 
                'errorcode' => '0', 
                'msg' => 'Business Category',
                'data' => $a,
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