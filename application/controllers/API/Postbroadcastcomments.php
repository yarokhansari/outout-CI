<?php
/*
User: Yogen Jajal
API Name: Post comment.
Parameter: deviceToken, operation
Description: API will register user details and send email to user on registered email address
*/

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class Postbroadcastcomments extends REST_Controller {

  function index() {
    $response = array();
    $where = array('accessToken' => $this->input->post('accessToken'));
    $devices = $this->model_name->select_data_by_condition('hoo_devices', $where, '*' ,'' , '' ,'', '', array());
    if(!empty($devices) || 4 == 4) {

      $user_id = $this->input->post('userid');
      $operation = $this->input->post('operation');
      $comment = $this->input->post('comment');
      $image_base64 = base64_decode($this->input->post('image'));
      $image_base64 =($this->input->post('image'));
      $file = $this->config->item('upload_path_user') . uniqid() . '.jpg';
      file_put_contents($file, $image_base64);
      $image = base_url() .  $file;
  
      $message = "";
      $result = array();
      if ($operation == "add") {
        $data = array(
          // "broadcast_id" => $b_id,
          "userid"=> $user_id,
          "comment" => $comment,
          "image" => $image,
          "created_at" => date('Y-m-d h:i:s')
        );

    $id = $this->model_name->insert_data_getid($data, "hoo_bcomments");
		
		$usercondition = array( 'is_delete' => '0','id' => $user_id );
		
		$userdetails = $this->model_name->select_data_by_condition("hoo_users", $usercondition, 'first_name,last_name' , '', '', '', '', array());
		
		$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
		
		$notification = array(
            "user_id" => $user_id,
            // "media_id" => $media_id,
            "description" => $fullname . 'posted a comment on post.',
            "created_at" => date('Y-m-d h:i:s')
        );
		$notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
	
        $result = $this->model_name->select_data_by_condition('hoo_comments', array('id' => $id), '*' ,'' , '' ,'', '', array());
        $message = "Comment inserted successfully.";

 
		

      }
    
      
      else if ($operation == "update") {
        // if ($comment_id > 0) {
          $data = array(
          "broadcast_id" =>$b_id,
          "userid"=> $user_id,
          "comment" => $comment,
          "image" => $image,
          "updated_at" => date('Y-m-d h:i:s')
          );
      $this->model_name->update_data($data, "hoo_bcomments", "broadcast_id",$user_id);
		  
		  $usercondition = array( 'is_delete' => '0','id' => $user_id );
		
		  $userdetails = $this->model_name->select_data_by_condition("hoo_users", $usercondition, 'first_name,last_name' , '', '', '', '', array());
		
		  $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
		  
		  $notification = array(
				"user_id" => $user_id,
				// "media_id" => $media_id,
				"description" => $fullname . 'updated a comment on post.',
				"created_at" => date('Y-m-d h:i:s')
		   );
		  $notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
		  
          $result = $this->model_name->select_data_by_condition('hoo_bcomments', array('id' => $user_id), '*' ,'' , '' ,'', '', array());
          $message = "Comment updated successfully.";
        // }
        // else {
        //   $message = "Operation couldn't performed. As Comment id not provided.";
        // }
      }
      else if ($operation == "delete") {
        // if ($comment_id > 0) {
        $this->model_name->delete_data("hoo_bcomments", "broadcast_id", $user_id);
		  
		  $usercondition = array( 'is_delete' => '0','id' => $user_id );
		
		  $userdetails = $this->model_name->select_data_by_condition("hoo_users", $usercondition, 'first_name,last_name' , '', '', '', '', array());
		
		  $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
		  
		  $notification = array(
				"user_id" => $user_id,
				// "media_id" => $media_id,
				"description" => $fullname . 'deleted a comment on post.',
				"created_at" => date('Y-m-d h:i:s')
		   );
		  $notifications = $this->model_name->insert_data_getid($notification, "hoo_notifications");
		  
      $message = "Comment deleted successfully.";
		  
		  // $userpoint = array(
			// 'user_id' => $user_id,
			// 'media_id' => $media_id,
			// 'points' => 0,
			// 'comments' => 'Added comment on post',
			// 'created_at' => date('Y-m-d h:i:s')
		  // );
			
		  // $points = $this->model_name->insert_data_getid($userpoint, "hoo_user_points");
        // }
        // else {
        //   $message = "Operation couldn't performed. As comment id not provided.";
        // }
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