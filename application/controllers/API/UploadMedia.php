<?php
/*
User: Yogen Jajal
API Name: Upload Media like Images, Video etc.
Parameter: intUdId,deviceToken,deviceType,firstname,lastname,DOB,emailid,password,contactno,otp
Description: API will register user details and send email to user on registered email address
 */

require 'BaseApi.php';

class UploadMedia extends BaseApi {

  function index() {
    $response = array();
    if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
      $user_id = $this->input->post('user_id');
      $media_type = $this->input->post('media_type');
	    $type = $this->input->post('type'); //Type = point id where user will get points according to action performed
      $caption = $this->input->post('caption');
      $tagged_users = $this->input->post('tagged_users');
	    $lat = $this->input->post('lat');
	    $long = $this->input->post('long');
	    $address = $this->input->post('address');
	    $description = $this->input->post('description');
      $bid = $this->input->post('business_id');
      $user = $this->getUser($user_id);
      $notification_description = "";

      if ($media_type == "0") { //image
        $actual_file_path = $this->config->item('upload_path_image');
        $thumbnail_file_path = $this->config->item('upload_path_thumbnail_image');
        $allowed_types = $this->config->item('upload_image_allowed_types');
        $uploadData = $this->uploadImageFile($actual_file_path, $allowed_types, uniqid(), "file");
        if ($uploadData) {
          $media_name = $uploadData["file_name"];
          $extension = $this->getExtension($uploadData["file_type"]);
          $media_url = base_url().$actual_file_path.$media_name;
          $result = $this->uploadImageThumbFile($actual_file_path.$media_name, $thumbnail_file_path, 200, 175);
          if ($result) {
            $media_thumbnail_url = base_url().$thumbnail_file_path.$media_name;
          }
          $notification_description = $user["first_name"]." ".$user["last_name"]." posted image.";
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
          $allowed_types = $this->config->item('upload_image_allowed_types');
          $uploadData = $this->uploadVideoFile($thumbnail_file_path, $allowed_types, uniqid(), "thumb_file");
          if ($uploadData) {
            $media_thumbnail_url = base_url().$thumbnail_file_path.$uploadData["file_name"];
          }
          $notification_description = $user["first_name"]." ".$user["last_name"]." posted video.";
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
		  "lat" => $lat,
		  "long" => $long,
		  "address" => $address,
		  "description" => $description,
          "created_at" => date('Y-m-d h:i:s')
        );

        $id = $this->model_name->insert_data_getid($data, "hoo_memory_media");
		
		//Get point details 
		$pointdetails = $this->model_name->select_data_by_condition('hoo_points', array('is_delete' => '0','id' => $type) , '*', '', '', '', '', array());
		
		$userpoint = array(
			'user_id' => $user_id,
			'media_id' => $id,
			'points' => $pointdetails[0]['points'],
			'comments' => 'Posted an image or video',
			'created_at' => date('Y-m-d h:i:s')
		);
		
		$points = $this->model_name->insert_data_getid($userpoint, "hoo_user_points");
		
    $inserted_data = $this->model_name->select_data_by_condition('hoo_memory_media', array('id' => $id), '*' ,'' , '' ,'', '', array());

    $check=$this->model_name->select_data_by_condition('hoo_allpoints', array("user_id"=>$user_id), '*' ,'' , '' ,'', '', array());
  
if($check==null) {
  $where = array("user_id" => $user_id, "is_delete" => "0");
  $likes = $this->model_name->select_data_by_condition('hoo_user_points', $where, '*' ,'' , '' ,'', '', array());
  // $count = count($likes);
  // $count=+1;
  $count=$likes[0]['points'];

  
  $media_data = array(

    "user_id" => $user_id,
    "totalpoints" =>$count
    // "updated_at" => date('Y-m-d h:i:s')
  );
  // $this->model_name->update_data($media_data, "hoo_allpoints", "id", $user_id);
  $this->model_name->insert_data_getid($media_data, "hoo_allpoints");
  // $this->model_name->insert_data_getid($media_data, "hoo_allpoints");
}
else{
  $where = array("user_id" => $user_id, "is_delete" => "0","created_at"=>date('y-m-d h:i:s'));
  $likes = $this->model_name->select_data_by_condition('hoo_user_points', $where, '*' ,'' , '' ,'', '', array());
  // $count = count($likes);
  $count=$likes[0]['points']+$check[0]['totalpoints'];

  // $count=$check['totalpoints']+1;

  
  $media_data = array(
    // "user_id" => $user_id,
    "totalpoints" =>$count
    // "updated_at" => date('Y-m-d h:i:s')
  );
 $this->model_name->update_data($media_data, "hoo_allpoints", "user_id", $user_id);
}


        $tags = array();
		
		if( $tagged_users!=""){
			$tagged_users_array = array_filter(explode(",", $tagged_users));
			foreach ($tagged_users_array as $key => $value) {
			  $tag = array(
				"media_id" => $id,
				"user_id" => $value,
        "business_id"=>$bid,
				"created_at" => date('Y-m-d h:i:s')
			  );
			  array_push($tags, $tag);
			}
			$this->model_name->insert_data_batch($tags, "hoo_tags");

		}else if($bid !="")
    {
      $tag = array(
				"media_id" => $id,
        "business_id"=>$bid,
				"created_at" => date('Y-m-d h:i:s')
			  );

        $this->model_name->insert_data_getid($tags, "hoo_tags");
    }
		

        $myFriends = $this->custom->getMyFriends($user_id);

        $notifications = array();
        foreach ($myFriends as $key => $value) {
          $notification = array(
            "user_id" => $value["user_id"],
            "media_id" => $id,
            "description" => $notification_description,
            "created_at" => date('Y-m-d h:i:s')
          );
          array_push($notifications, $notification);
        }
        $this->model_name->insert_data_batch($notifications, "hoo_notifications");

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
          'errorcode' => '1',
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
}

?>