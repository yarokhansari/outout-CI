<?php
/*
User: Yarokh Ansari
API Name: Like Post
Parameter: deviceToken, operation
Description: API will register user details and send email to user on registered email address
*/

require 'BaseApi.php';

class Report extends BaseApi {

  function index() {
    $response = array();
    if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
      $user_id = $this->input->post('user_id');
      $media_id = $this->input->post('media_id');
      $message1 = $this->input->post('message');
      $is_report = $this->input->post('is_report');
      
    //   $totalpoints = $this->input->post('totalpoints');
      $id = $this->input->post('id');
	//   $type = $this->input->post('type'); //Type = point id where user will get points according to action performed
      $like_where = array('user_id' => $user_id, "media_id" => $media_id);

      $message = "";
      $result = $this->model_name->selectRecordByFields('hoo_report', $like_where);
      if ($result) {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "message" =>$message1,
          "is_report" =>$is_report,
          "updated_at" => date('Y-m-d h:i:s')
        );
        $this->model_name->update_data($data, "hoo_report", "id", $result["id"]);
        $result = $this->model_name->select_data_by_condition('hoo_report', array('id' => $result["id"]), '*' ,'' , '' ,'', '', array());
	    	$message = "";
        $message = "UnReport Media Successfully.";
      }
      else {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "message" =>$message1,
          "is_report" =>$is_report,
          "created_at" => date('Y-m-d h:i:s')
        );

        $id = $this->model_name->insert_data_getid($data, "hoo_report");
        $result = $this->model_name->select_data_by_condition('hoo_report', array('id' => $id), '*' ,'' , '' ,'', '', array());
		$message = "";
        $message = "Report Media Successfully.";
		
		$notification = array(
            "user_id" => $user_id,
            "media_id" => $media_id,
            "description" => 'Report Media successfully.',
            "created_at" => date('Y-m-d h:i:s')
        );
		$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications"); 

    $likes = $this->model_name->select_data_by_condition('hoo_memory_media', array('id'=>$media_id), '*' ,'' , '' ,'', '', array());
      $count =$likes[0]['media_url'];
    $getid = $this->model_name->select_data_by_condition('hoo_memory_media', array('id'=>$media_id), 'user_id', '', '','', '' , array());
    $userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $user_id, 'is_delete' => '0'), '*' , '', '', '', '', array());
    $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
    $devicedetails = $this->model_name->select_data_by_condition("hoo_devices", array( 'user_id' =>$getid[0]['user_id'] ), '*' , '', '', '', '', array());

    $notificationData = array(
      "title"=> "$fullname Reported On Your Photo!",
      "body" => "Click to see the media",
      'image'=> $count,
      'style' => 'picture',
      'picture'=> $count,
      "mutable_content"=> true,
      "sound"=> "Tri-tone",
     
    );

    $data = array(
      'notification_type' => '8',
      "media_id"=>$media_id, 
    );
    
    $notification = $this->model_name->sendNotification(
      $devicedetails[0]['fcmToken'],
      $data,
      $notificationData
    );



		
		//Get point details 
	// 	$pointdetails = $this->model_name->select_data_by_condition('hoo_points', array('is_delete' => '0','id' => $type) , '*', '', '', '', '', array());
		
	// 	$userpoint = array(
	// 		'user_id' => $user_id,
	// 		'media_id' => $media_id,
	// 		'points' => $pointdetails[0]['points'],
	// 		'comments' => 'Gold star',
	// 		'created_at' => date('Y-m-d h:i:s')
	// 	);
    // $points = $this->model_name->insert_data_getid($userpoint, "hoo_user_points");
    // // 
		
      }
		
      //$this->updategoldstarCount($user_id)


    //  $check=$this->model_name->select_data_by_condition('hoo_allpoints', array("user_id"=>$user_id), '*' ,'' , '' ,'', '', array());
  
    //   if($check==null) {
    //     $where = array("user_id" => $user_id, "is_delete" => "0");
    //     $likes = $this->model_name->select_data_by_condition('hoo_user_points', $where, '*' ,'' , '' ,'', '', array());
    //     // $count = count($likes);
    //     // $count=+1;
    //     $count=$likes[0]['points'];
    
        
    //     $media_data = array(
  
    //       "user_id" => $user_id,
    //       "totalpoints" =>$count
    //       // "updated_at" => date('Y-m-d h:i:s')
    //     );
    //     // $this->model_name->update_data($media_data, "hoo_allpoints", "id", $user_id);
    //     $this->model_name->insert_data_getid($media_data, "hoo_allpoints");
    //     // $this->model_name->insert_data_getid($media_data, "hoo_allpoints");
    //   }
    //   else{
    //     $where = array("user_id" => $user_id, "is_delete" => "0","created_at"=>date('y-m-d h:i:s'));
    //     $likes = $this->model_name->select_data_by_condition('hoo_user_points', $where, '*' ,'' , '' ,'', '', array());
    //     // $count = count($likes);
    //     $count=$likes[0]['points']+$check[0]['totalpoints'];

    //     // $count=$check['totalpoints']+1;
  
        
    //     $media_data = array(
    //       // "user_id" => $user_id,
    //       "totalpoints" =>$count
    //       // "updated_at" => date('Y-m-d h:i:s')
    //     );
    //    $this->model_name->update_data($media_data, "hoo_allpoints", "user_id", $user_id);
    // }
    

    
      //$this->updategoldstarCount($media_id);
      $where = array("media_id" => $media_id, "is_delete" => "0","is_report" => '1');
      $likes = $this->model_name->select_data_by_condition('hoo_report', $where, '*' ,'' , '' ,'', '', array());
      $count = count($likes);

      $media_data = array(
        "report" => $count,
        "updated_at" => date('Y-m-d h:i:s')
      );
      $this->model_name->update_data($media_data, "hoo_memory_media", "id", $media_id);

      // 


      
      if($likes[0]['report']>5)
      {

      $media_data = array(
        "image" => $count,
        "updated_at" => date('Y-m-d h:i:s')
      );
      $this->model_name->update_data($media_data, "hoo_report", "media_id", $media_id);
    }
      // 

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