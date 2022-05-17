<?php
/*
User: Yarokh Ansari
API Name: Fire 
Parameter: deviceToken, operation
Description: API will register user details and send email to user on registered email address
*/

require 'BaseApi.php';

class Fire extends BaseApi {

  function index() {
    $response = array();
    if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
      $user_id = $this->input->post('user_id');
      $media_id = $this->input->post('media_id');
      $fire = $this->input->post('fire');
      $totalpoints = $this->input->post('totalpoints');
      $id = $this->input->post('id');
	  $type = $this->input->post('type'); //Type = point id where user will get points according to action performed
      $like_where = array('user_id' => $user_id, "media_id" => $media_id);

      $message = "";
      $result = $this->model_name->selectRecordByFields('hoo_fire', $like_where);
      if ($result) {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "fire" =>$fire,
          "updated_at" => date('Y-m-d h:i:s')
        );
        $this->model_name->update_data($data, "hoo_fire", "id", $result["id"]);
        $result = $this->model_name->select_data_by_condition('hoo_fire', array('id' => $result["id"]), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Fire unliked successfully.";
      }
      else {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "fire" =>$fire,
          "created_at" => date('Y-m-d h:i:s')
        );
        $id = $this->model_name->insert_data_getid($data, "hoo_fire");
        $result = $this->model_name->select_data_by_condition('hoo_fire', array('id' => $id), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Fire liked successfully.";
		
		$notification = array(
            "user_id" => $user_id,
            "media_id" => $media_id,
            "description" => 'You Got 🔥 On Posts.',
            "created_at" => date('Y-m-d h:i:s')
        );
		$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");  
      }
      // 

      //Get point details 
      
      $check=$this->model_name->select_data_by_condition('hoo_user_points', array("user_id"=>$user_id), '*' ,'' , '' ,'', '', array());

      if($check==null)
      {
      $pointdetails = $this->model_name->select_data_by_condition('hoo_fire', array('is_delete' => '0','user_id' =>$user_id) , '*', '', '', '', '', array());
      $count=count($pointdetails);
      if($count %5==0)
    {
      
      $userpoint = array(
        'user_id' => $user_id,
        // 'media_id' => $media_id,
        'points' =>'2',
        'comments' => 'Fire added',
        'created_at' => date('Y-m-d h:i:s')
      );
      $points = $this->model_name->insert_data_getid($userpoint, "hoo_user_points");
    }
  }
  else{
    $pointdetails = $this->model_name->select_data_by_condition('hoo_fire', array('is_delete' => '0','user_id' =>$user_id) , '*', '', '', '', '', array());
    $count=$check[0]['points'];
    $count1=count($pointdetails);
    if($count1 %5==0)
  {
    $media_data = array(
      "points" =>$count+2,
      "updated_at" => date('Y-m-d h:i:s')
    );
   $this->model_name->update_data($media_data, "hoo_user_points", "user_id", $user_id);
  }
}


      // get all points


$check=$this->model_name->select_data_by_condition('hoo_allpoints', array("user_id"=>$user_id), '*' ,'' , '' ,'', '', array());

if($check==null)
{
$pointdetails = $this->model_name->select_data_by_condition('hoo_fire', array('is_delete' => '0','user_id' =>$user_id) , '*', '', '', '', '', array());
$count=count($pointdetails);
if($count %5==0)
{

$userpoint = array(
  'user_id' => $user_id,
  // 'media_id' => $media_id,
  'totalpoints' =>'2',
  // 'comments' => 'Gold Star',
  'created_at' => date('Y-m-d h:i:s')
);
$points = $this->model_name->insert_data_getid($userpoint, "hoo_allpoints");
}
}
else{
$pointdetails = $this->model_name->select_data_by_condition('hoo_fire', array('is_delete' => '0','user_id' =>$user_id) , '*', '', '', '', '', array());
$count=$check[0]['totalpoints'];
$count1=count($pointdetails);
if($count1 %5==0)
{
$media_data = array(
"totalpoints" =>$count+2,
"updated_at" => date('Y-m-d h:i:s')
);
$this->model_name->update_data($media_data, "hoo_allpoints", "user_id", $user_id);
}
}
    
      //$this->updategoldstarCount($media_id);
      $where = array("media_id" => $media_id, "is_delete" => "0","fire" => '1');
      $likes = $this->model_name->select_data_by_condition('hoo_fire', $where, '*' ,'' , '' ,'', '', array());
      $count = count($likes);

      $media_data = array(
        "fire" => $count,
        "updated_at" => date('Y-m-d h:i:s')
      );
      $this->model_name->update_data($media_data, "hoo_memory_media", "id", $media_id);
      $response = array(
        'status' => 'success', 
        'errorcode' => '0', 
        'msg' => $message,
        'data' => $result
      );
      }

    else {
      $response = array(
        'status' => 'failure', 
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