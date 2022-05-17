<?php
/*
User: Yogen Jajal
API Name: Upload Media like Images, Video etc.
Parameter: intUdId,deviceToken,deviceType,firstname,lastname,DOB,emailid,password,contactno,otp
Description: API will register user details and send email to user on registered email address
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class UploadMedia extends REST_Controller {
  private $error = "";
  function index() {
    $response = array();
    $where = array('accessToken' => $this->input->post('accessToken'));
    $devices = $this->model_name->select_data_by_condition('hoo_devices', $where, '*' ,'' , '' ,'', '', array());
    if(!empty($devices) || 4 == 4) {

      $user_id = $this->input->post('user_id');
      $media_type = $this->input->post('media_type');
      $caption = $this->input->post('caption');

      if ($media_type == "0") { //image
        $actual_file_path = $this->config->item('upload_path_image');
        $thumbnail_file_path = $this->config->item('upload_path_thumbnail_image');
        $allowed_types = $this->config->item('upload_image_allowed_types');
        $uploadData = $this->uploadImageFile($actual_file_path, $allowed_types, uniqid());
        if ($uploadData) {
          $media_name = $uploadData["file_name"];
          $extension = $this->getExtension($uploadData["file_type"]);
          $media_url = base_url().$actual_file_path.$media_name;
          $result = $this->uploadImageThumbFile($actual_file_path.$media_name, $thumbnail_file_path, 200, 175);
          if ($result) {
            $media_thumbnail_url = base_url().$thumbnail_file_path.$media_name;
          }
        }
      }
      else if ($media_type == "1") { //video
        $actual_file_path = $this->config->item('upload_path_video');
        $thumbnail_file_path = $this->config->item('upload_path_thumbnail_video');
        $allowed_types = $this->config->item('upload_video_allowed_types');
        $uploadData = $this->uploadVideoFile($actual_file_path, $allowed_types, uniqid());
        if ($uploadData) {
          $media_name = $uploadData["file_name"];
          $extension = $this->getExtension($uploadData["file_type"]);
          $media_url = base_url().$actual_file_path.$media_name;
          $media_thumbnail_url = "";
        }
      }
      else {
        $this->error .= "Invalid media type.";
      }

      if ($this->error == "") {
        $data = array(
          "user_id" => $user_id,
          "media_type"=> $media_type,
          "media_name" => $media_name,
          "media_extension" => $extension,
          "media_url" => $media_url,
          "media_thumbnail" => $media_thumbnail_url,
          "caption" => $caption,
          "created_at" => date('Y-m-d h:i:s')
        );

        $id = $this->model_name->insert_data_getid($data, "hoo_memory_media");
        $inserted_data = $this->model_name->select_data_by_condition('hoo_memory_media', array('id' => $id), '*' ,'' , '' ,'', '', array());

        $response = array(
          'status' => 'Success', 
          'errorcode' => '0', 
          'msg' => ($media_type == 0 ? "Image uploaded successfully." : "Video uploaded successfully."),
          'data' => $inserted_data
        );
      }
      else {
        $response = array(
          'status' => 'Failed',
          'errorcode' => '2',
          'msg' => $this->error
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

  function uploadImageFile($file_path, $allowed_types, $file_name) {
    $config['upload_path'] = $file_path;
    $config['allowed_types'] = $allowed_types;
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