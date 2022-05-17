<?php
/*
Author: Yogen Jajal
API Name: Base API class
Parameter: 
Description: 
*/

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class BaseApi extends REST_Controller {
    public $error = "";
    public function validateAccessToken($accessToken) {
        $where = array('accessToken' => $accessToken);
        $devices = $this->model_name->select_data_by_condition('hoo_devices', $where, '*' ,'' , '' ,'', '', array());
        return !empty($devices);
    }

    public function isUserExists($user_id) {
        $where = array('id' => $user_id);
        $user = $this->model_name->select_data_by_condition('hoo_users', $where, '*' ,'' , '' ,'', '', array());
        return !empty($user);
    }

    public function getUser($user_id) {
        $where = array('id' => $user_id);
        return $this->model_name->selectRecordByFields('hoo_users', $where);
    }

    function getExtension($mime_type) {
        $extensions = array(
            'image/jpeg' => 'jpeg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'text/gif' => 'gif',
            'video/mp4' => 'mp4',
            'video/avi' => 'avi',
            'video/flv' => 'flv',
            'video/mkv' => 'mkv',
            'video/mov' => 'mov'
        );
        if ($extensions[$mime_type])
            return $extensions[$mime_type];
        else
            return $mime_type;
    }

    function uploadImageFile($file_path, $allowed_types, $file_name, $param) {
        $config['upload_path'] = $file_path;
        $config['allowed_types'] = $allowed_types;
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload($param))
        {
            return $this->upload->data();
        }
        else {
            $this->error .= $this->upload->display_errors('', '');
            return array();
        }
    }

    function uploadImageThumbFile($source_path, $thumb_path, $thumb_width, $thumb_height) {
        $config['image_library'] = "gd2";
        $config['source_image'] = $source_path;
        $config['new_image'] = $thumb_path;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $thumb_width;
        $config['height'] = $thumb_height;
        $this->load->library('image_lib', $config);

        if($this->image_lib->resize())
        {
            return array("status" => "success");
        }
        else {
            $this->error .= $this->image_lib->display_errors('', '');
            return array();
        }
    }

    function uploadVideoFile($file_path, $allowed_types, $file_name) {
        $config['upload_path'] = $file_path;
        $config['allowed_types'] = $allowed_types;
        $config['max_size'] = '0'; //allowed max file size. 0 means unlimited file size
        $config['max_filename'] = '255';
        $config['encrypt_name'] = FALSE;
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);

        if($this->upload->do_upload('file'))
        {
            return $this->upload->data();
        }
        else {
            $this->error .= $this->upload->display_errors('', '');
            return array();
        }
    }
}

?>