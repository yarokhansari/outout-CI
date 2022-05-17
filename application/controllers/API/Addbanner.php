<?php

require 'BaseApi.php';

class Addbanner extends BaseApi {
    function index() {
        $response = array();
          $id = $this->input->post('id');
            $image_base64 = base64_decode($this->input->post('banner'));
            $file = $this->config->item('upload_path_user') . uniqid() . '.jpg';
            file_put_contents($file, $image_base64);
            $image = base_url() .  $file;
            if($this->isUserExists($id)) {
                $data = array(
                    "banner" => $image,
                );

                if ($this->model_name->update_data($data, 'hoo_users','id', $id)) {
                    $event = $this->model_name->select_data_by_condition('hoo_users', array('id' => $id), '*', '', '', '', '', array());
                    $response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'banner successfully', 'data' => $event);
                }
                else {
                    $response = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'banner not updated.');
                }
            }
            else {
                $response = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Id is incorrect');
            }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }  
}