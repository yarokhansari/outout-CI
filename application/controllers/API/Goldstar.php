<?php
/*
User: Yogen Jajal
API Name: Like Post
Parameter: deviceToken, operation
Description: API will register user details and send email to user on registered email address
*/

require 'BaseApi.php';

class Goldstar extends BaseApi {

  function index() {
    $response = array();
    if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
      $user_id = $this->input->post('user_id');
      $media_id = $this->input->post('media_id');
      $goldstar = $this->input->post('gold_star');
      $totalpoints = $this->input->post('totalpoints');
      $id = $this->input->post('id');
	  $type = $this->input->post('type'); //Type = point id where user will get points according to action performed
      $like_where = array('user_id' => $user_id, "media_id" => $media_id);

      $message = "";
      $result = $this->model_name->selectRecordByFields('hoo_goldstar', $like_where);
      if ($result) {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "gold_star" =>$goldstar,
          "updated_at" => date('Y-m-d h:i:s')
        );
        $this->model_name->update_data($data, "hoo_goldstar", "id", $result["id"]);
        $result = $this->model_name->select_data_by_condition('hoo_goldstar', array('id' => $result["id"]), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Goldstar unliked successfully.";
      }
      else {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "gold_star" =>$goldstar,
          "created_at" => date('Y-m-d h:i:s')
        );
        $id = $this->model_name->insert_data_getid($data, "hoo_goldstar");
        $result = $this->model_name->select_data_by_condition('hoo_goldstar', array('id' => $id), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Goldstar liked successfully.";
		
		$notification = array(
            "user_id" => $user_id,
            "media_id" => $media_id,
            "description" => 'GoldStar liked successfully.',
            "created_at" => date('Y-m-d h:i:s')
        );
		$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");  
		
		//Get point details 
    $check=$this->model_name->select_data_by_condition('hoo_user_points', array("user_id"=>$user_id), '*' ,'' , '' ,'', '', array());
  
    // if($check==null) {
      
		$pointdetails = $this->model_name->select_data_by_condition('hoo_goldstar', array('is_delete' => '0','user_id' =>$user_id,'gold_star'=>'1') , '*', '', '', '', '', array());
    $count=count($pointdetails);
    if($count %5==0)
  {
		$userpoint = array(
			'user_id' => $user_id,
			'points' =>'2',
			'comments' => 'GoldStar Added',
			'created_at' => date('Y-m-d h:i:s')
		);	
		$points = $this->model_name->insert_data_getid($userpoint, "hoo_user_points");
  }	
// }
// else{
  if($check!=null){
  $pointdetails = $this->model_name->select_data_by_condition('hoo_goldstar', array('is_delete' => '0','user_id' =>$user_id,'gold_star'=>'1') , '*', '', '', '', '', array());
    $count=$check[0]['points'];
    $count1=count($pointdetails);
   if($count1 %5==0)
   {
    $userpoint = array(
			'points' =>$count+2,
      "updated_at" => date('Y-m-d h:i:s')
		);
		$points = $this->model_name->update_data($userpoint, "hoo_user_points","user_id",$user_id);
  
      }
    }
    // }
  }


  // get all points

  $check=$this->model_name->select_data_by_condition('hoo_allpoints', array("user_id"=>$user_id), '*' ,'' , '' ,'', '', array());

if($check==null)
{
$pointdetails = $this->model_name->select_data_by_condition('hoo_goldstar', array('is_delete' => '0','user_id' =>$user_id) , '*', '', '', '', '', array());
$count=count($pointdetails);
if($count %5==0)
{

$userpoint = array(
  'user_id' => $user_id,
  'totalpoints' =>'2',
  'created_at' => date('Y-m-d h:i:s')
);
$points = $this->model_name->insert_data_getid($userpoint, "hoo_allpoints");
}
}
else{
$pointdetails = $this->model_name->select_data_by_condition('hoo_goldstar', array('is_delete' => '0','user_id' =>$user_id) , '*', '', '', '', '', array());
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
      $where = array("media_id" => $media_id, "is_delete" => "0","gold_star" => '1');
      $likes = $this->model_name->select_data_by_condition('hoo_goldstar', $where, '*' ,'' , '' ,'', '', array());
      $count = count($likes);

      $media_data = array(
        "goldstar" => $count,
        "updated_at" => date('Y-m-d h:i:s')
      );
      $this->model_name->update_data($media_data, "hoo_memory_media", "id", $media_id);
      $response = array(
        'status' => 'success', 
        'errorcode' => '0', 
        'msg' => $message,
        'data' => $result
      );
       $getid = $this->model_name->select_data_by_condition('hoo_memory_media', array('id'=>$media_id), 'user_id', '', '','', '' , array());
      $friend_id=$getid[0]['user_id'];
      $userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $friend_id, 'is_delete' => '0'), '*' , '', '', '', '', array());
      $devicedetails = $this->model_name->select_data_by_condition("hoo_devices", array( 'user_id' => $friend_id ), '*' , '', '', '', '', array());
      $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
        
      $notificationData = array(
        "title"=> "$fullname Gave You a Star!",
        "body" => "",
        "mutable_content"=> true,
        "sound"=> "Tri-tone"
       
      );
  
      $data = array(
        'notification_type' => '0',
        'isBroadCaster' => true,
        'hostUserId' => $userid
      );
      
      $notification = $this->model_name->sendNotification(
        $devicedetails[0]['fcmToken'],
        $data,
        $notificationData
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