<?php
/*
User: Yogen Jajal
API Name: View Post
Parameter: deviceToken, operation
Description: API will register user details and send email to user on registered email address
*/

require 'BaseApi.php';

class ViewPost extends BaseApi {

  function index() {
    $response = array();
    if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
      $user_id = $this->input->post('user_id');
      $media_id = $this->input->post('media_id');
      $like_where = array('user_id' => $user_id, "media_id" => $media_id);

      $message = "";
      $result = $this->model_name->selectRecordByFields('hoo_views', $like_where);
      if (empty($result)) {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "created_at" => date('Y-m-d h:i:s')
        );
        $id = $this->model_name->insert_data_getid($data, "hoo_views");
        $result = $this->model_name->select_data_by_condition('hoo_views', array('id' => $id), '*' ,'' , '' ,'', '', array());
        $message = "Post viewed successfully.";
      }

      //$this->updateLikesCount($media_id);
      $where = array("media_id" => $media_id, "is_delete" => "0");
      $views = $this->model_name->select_data_by_condition('hoo_views', $where, '*' ,'' , '' ,'', '', array());
      $count = count($views);

      $media_data = array(
        "views" => $count,
        "updated_at" => date('Y-m-d h:i:s')
      );
      $this->model_name->update_data($media_data, "hoo_memory_media", "id", $media_id);
      $response = array(
        'status' => 'Success', 
        'errorcode' => '0', 
        'msg' => $message,
        'data' => $result
      );
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