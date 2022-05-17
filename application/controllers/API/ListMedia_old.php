<?php
/*
Author: Yogen Jajal
API Name: Category Listing
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class ListMedia extends REST_Controller {
    function index() {
        $response = array();
        $where = array('accessToken' => $this->input->post('accessToken'));
        $devices = $this->model_name->select_data_by_condition('hoo_devices', $where, '*' ,'' , '' ,'', '', array());
        if(!empty($devices) || 4 == 4) {
            $offset = $this->input->post('offset');
            $limit = $this->input->post('limit');
            $media_type = $this->input->post('media_type');
            //$media_id = $this->input->post('media_id');
            $user_id = $this->input->post('user_id');

            $media_where = array("is_delete" => "0");
            /*if ($media_id > 0)
            $media_where["id"] = $media_id;*/

            if ($user_id > 0)
                $media_where["user_id"] = $user_id;

            if ($media_type != "")
                $media_where["media_type"] = $media_type;

            if ($limit == 0 || $limit == "")
                $limit = 10;

            if ($offset >= 0) {
                $media = $this->model_name->select_data_by_condition('hoo_memory_media', $media_where, '*', 'id', 'desc', $limit, $offset, array());

                if(!empty($media)) {

                    foreach ($media as $key => $value) {
                        $user = $this->model_name->selectRecordById("hoo_users", $value["user_id"], "id");
                        $media[$key]["username"] = $user["username"];
                        $media[$key]["profile_image"] = $user["profile_image"];
                    }

                    $response = array(
                        'status' => 'Success', 
                        'errorcode' => '0', 
                        'msg' => 'List Media',
                        'data' => $media
                    );
                }
                else {
                    $response = array(
                        'status' => 'Failed', 
                        'errorcode' => '1',
                        'msg' => 'No media found.'
                    );
                }
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'Please enter offset value.'
                );
            }
        }
        else {
          $response = array(
            'status' => 'Failed', 
            'errorcode' => '2',
            'msg' => 'Access Token is incorrect'
        );
      }

      header('Content-Type: application/json');
      echo json_encode($response);
      exit;

  }
}

?>