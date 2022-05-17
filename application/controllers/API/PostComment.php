<?php
/*
User: Yogen Jajal
API Name: Post comment.
Parameter: deviceToken, operation
Description: API will register user details and send email to user on registered email address
*/

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class PostComment extends REST_Controller {

  function index() {
    $response = array();
    $where = array('accessToken' => $this->input->post('accessToken'));
    $devices = $this->model_name->select_data_by_condition('hoo_devices', $where, '*' ,'' , '' ,'', '', array());
    if(!empty($devices) || 4 == 4) {

      $user_id = $this->input->post('user_id');
      $media_id = $this->input->post('media_id');
      $operation = $this->input->post('operation');
      $comment_id = $this->input->post('comment_id');
      $comment = $this->input->post('comment');
      $totalpoints = $this->input->post('totalpoints');
      $id = $this->input->post('id');
	  $type = $this->input->post('type'); //Type = point id where user will get points according to action performed
	  
	  

      $message = "";
      $result = array();
      if ($operation == "add") {
        $data = array(
          "user_id" => $user_id,
          "media_id"=> $media_id,
          "comment" => $comment,
          "created_at" => date('Y-m-d h:i:s')
        );

        $id = $this->model_name->insert_data_getid($data, "hoo_comments");
		
		$usercondition = array( 'is_delete' => '0','id' => $user_id );
		
		$userdetails = $this->model_name->select_data_by_condition("hoo_users", $usercondition, 'first_name,last_name' , '', '', '', '', array());
		
		$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
		
		$notification = array(
            "user_id" => $user_id,
            "media_id" => $media_id,
            "description" => $fullname . 'posted a comment on post.',
            "created_at" => date('Y-m-d h:i:s')
        );
		$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
	
        $result = $this->model_name->select_data_by_condition('hoo_comments', array('id' => $id), '*' ,'' , '' ,'', '', array());
        $message = "Comment inserted successfully.";

 
		
		//Get point details 
		$pointdetails = $this->model_name->select_data_by_condition('hoo_points', array('is_delete' => '0','id' => $type) , '*', '', '', '', '', array());
		
		$userpoint = array(
			'user_id' => $user_id,
			'media_id' => $media_id,
			'points' => $pointdetails[0]['points'],
			'comments' => 'Added comment on post',
			'created_at' => date('Y-m-d h:i:s')
		);
		
		$points = $this->model_name->insert_data_getid($userpoint, "hoo_user_points");


    $check=$this->model_name->select_data_by_condition('hoo_allpoints', array("user_id"=>$user_id), '*' ,'' , '' ,'', '', array());
  
    if($check==null) {
      $where = array("user_id" => $user_id, "is_delete" => "0");
      $likes = $this->model_name->select_data_by_condition('hoo_user_points', $where, '*' ,'' , '' ,'', '', array());
      // $count = count($likes);
      $count=$likes[0]['points'];
      

      $media_data = array(

        "user_id" => $user_id,
        "totalpoints" => $count
        // "updated_at" => date('Y-m-d h:i:s')
      );
      // $this->model_name->update_data($media_data, "hoo_allpoints", "id", $user_id);
      $this->model_name->insert_data_getid($media_data, "hoo_allpoints");
      // $this->model_name->insert_data_getid($media_data, "hoo_allpoints");
    }
    else{
      $where = array("user_id" => $user_id,"is_delete" => "0","created_at"=>date('y-m-d h:i:s'));
      $likes = $this->model_name->select_data_by_condition('hoo_user_points', $where, '*' ,'' , '' ,'', '', array());
      // echo 'hello'.$likes;
      // $count = count($likes);
      $count=$likes[0]['points']+$check[0]['totalpoints'];

      $media_data = array(
        // "user_id" => $user_id,
        "totalpoints" =>$count
        // "updated_at" => date('Y-m-d h:i:s')
      );
     $this->model_name->update_data($media_data, "hoo_allpoints", "user_id", $user_id);
  }
      }
    
      

      
      else if ($operation == "update") {
        if ($comment_id > 0) {
          $data = array(
            "user_id" => $user_id,
            "media_id"=> $media_id,
            "comment" => $comment,
            "created_at" => date('Y-m-d h:i:s')
          );
          $this->model_name->update_data($data, "hoo_comments", "id", $comment_id);
		  
		  $usercondition = array( 'is_delete' => '0','id' => $user_id );
		
		  $userdetails = $this->model_name->select_data_by_condition("hoo_users", $usercondition, 'first_name,last_name' , '', '', '', '', array());
		
		  $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
		  
		  $notification = array(
				"user_id" => $user_id,
				"media_id" => $media_id,
				"description" => $fullname . 'updated a comment on post.',
				"created_at" => date('Y-m-d h:i:s')
		   );
		  $notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
		  
          $result = $this->model_name->select_data_by_condition('hoo_comments', array('id' => $comment_id), '*' ,'' , '' ,'', '', array());
          $message = "Comment updated successfully.";
        }
        else {
          $message = "Operation couldn't performed. As Comment id not provided.";
        }
      }
      else if ($operation == "delete") {
        if ($comment_id > 0) {
          $this->model_name->delete_data("hoo_comments", "id", $comment_id);
		  
		  $usercondition = array( 'is_delete' => '0','id' => $user_id );
		
		  $userdetails = $this->model_name->select_data_by_condition("hoo_users", $usercondition, 'first_name,last_name' , '', '', '', '', array());
		
		  $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
		  
		  $notification = array(
				"user_id" => $user_id,
				"media_id" => $media_id,
				"description" => $fullname . 'deleted a comment on post.',
				"created_at" => date('Y-m-d h:i:s')
		   );
		  $notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
		  
          $message = "Comment deleted successfully.";
		  
		  $userpoint = array(
			'user_id' => $user_id,
			'media_id' => $media_id,
			'points' => 0,
			'comments' => 'Added comment on post',
			'created_at' => date('Y-m-d h:i:s')
		  );
			
		  $points = $this->model_name->insert_data_getid($userpoint, "hoo_user_points");
        }
        else {
          $message = "Operation couldn't performed. As comment id not provided.";
        }
      }

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