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

    public function updateLikesCount($media_id) {
      $where = array("media_id" => $media_id, "is_delete" => "0", "is_liked" => 1);
      $likes = $this->model_name->select_data_by_condition('hoo_likes', $where, '*' ,'' , '' ,'', '', array());
      $count = count($likes);

      $data = array(
          "likes" => $count,
          "updated_at" => date('Y-m-d h:i:s')
      );
      $this->model_name->update_data($data, "hoo_memory_media", "id", $media_id);
    }
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

    function getSuccessResponse($msg = "", $data = array()) {
        return array(
            "status" => "Success",
            "errorcode" => "0",
            "msg" => $msg,
            "data" => $data,
        );
    }

    function getFailedResponse($msg = "", $data = array()) {
        return array(
            "status" => "Failed",
            "errorcode" => "1",
            "msg" => $msg,
            "data" => $data,
        );
    }

    function getInvalidTokenResponse() {
        return array(
            "status" => "Failed",
            "errorcode" => "2",
            "msg" => "Invalid access token."
        );
    }

    function getUserNotFoundResponse() {
        return array(
            "status" => "Failed",
            "errorcode" => "1",
            "msg" => "User does not exists."
        );
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

        if($this->upload->do_upload($param)) {
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

        if($this->image_lib->resize()) {
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

        if($this->upload->do_upload('file')) {
            return $this->upload->data();
        }
        else {
            $this->error .= $this->upload->display_errors('', '');
            return array();
        }
    }

    function send_pushnotification($notification_text, $device_token) {
        $payload = array();
        $payload['aps'] = array(
            'alert' => $notification_text, //Message to be displayed in Alert
            //'data' => $dataVal,
            'badge' => '1', //Number to be displayed in the top right of your app icon this should not be a string
            'sound' => 'default' //Default notification sound (you can customize this)
        );
        $payload = json_encode($payload);
        if (ENVIRONMENT_NOTIFICATION == "DEVELOPMENT") {
            $apnsHost = 'gateway.sandbox.push.apple.com';
            $apnsCert = ABSURL . '/application/models/API/Dev_Health.pem';
            $passphrase = '';
        } else { // PRODUCTION
            $apnsHost = 'gateway.push.apple.com';
            $apnsCert = ABSURL . '/application/models/API/apns-prod.pem';
            $passphrase = '';
        }
        $apnsPort = 2195;
        $streamContext = @stream_context_create();
        @stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
        stream_context_set_option($streamContext, 'ssl', 'passphrase', $passphrase);
        $apns = @stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
        // 60 is the timeout
        $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', @str_replace(' ', '', $device_token)) . chr(0) . chr(strlen($payload)) . $payload;
        $result = @fwrite($apns, $apnsMessage);
        // socket_close($apns);
        @fclose($apns);
        // Push Notification Code End Here
        if (!$result) {
            $msg = 'Message not delivered' . PHP_EOL;
        } else {
            $msg = 'MSG Delivered';
        }
        return $msg;
    }



}

?>