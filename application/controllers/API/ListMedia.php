<?php
/*
Author: Yogen Jajal
API Name: Category Listing
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

require 'BaseApi.php';

class ListMedia extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $offset = $this->input->post('offset');
            $limit = $this->input->post('limit');
            $media_type = $this->input->post('media_type');
            $media_id = $this->input->post('media_id');
            $user_id = $this->input->post('user_id');

            $media_where = array("is_delete" => "0");
            if ($media_id > 0 && $media_id != "")
                $media_where["id"] = $media_id;

            if ($user_id > 0)
                $media_where["user_id"] = $user_id;

            if ($media_type != "")
                $media_where["media_type"] = $media_type;

            $start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;

            $usermedia = $this->model_name->select_data_by_condition('hoo_memory_media', $media_where, 'id,user_id,media_type,media_name,media_extension,media_url,media_thumbnail,caption,likes,views,goldstar,status,lat,long,address,report' ,'id', 'desc', $limit, $start_index, array());

            // $usermedia = $this->model_name->select_data_by_condition('hoo_memory_media', $media_where, 'id,user_id,media_type,media_name,media_extension,media_url,media_thumbnail,caption,likes,views,status,lat,long,address' ,'id', 'desc', array());

            if(!empty($usermedia)) {

                foreach ($usermedia as $key => $value) {
					
					$mediainfo[$key]['id'] = $value['id'];
					if( $value['type'] == 0 ){
						$mediainfo[$key]['type'] = 'Image';
					}else{
						$mediainfo[$key]['type'] = 'Video';
					}
					if( $value['media_name'] != "" ){
						$mediainfo[$key]['media_name'] = $value['media_name'];
					}else{
						$mediainfo[$key]['media_name'] = "";
					}
					
					if( $value['media_extension'] != "" ){
						$mediainfo[$key]['media_extension'] = $value['media_extension'];
					}else{
						$mediainfo[$key]['media_extension'] = "";
					}
					
					if( $value['media_url'] != "" ){
						$mediainfo[$key]['media_url'] = $value['media_url'];
					}else{
						$mediainfo[$key]['media_url'] = "";
					}
					
					if( $value['media_thumbnail'] != "" ){
						$mediainfo[$key]['media_thumbnail'] = $value['media_thumbnail'];
					}else{
						$mediainfo[$key]['media_thumbnail'] = "";
					}
					
					if( $value['caption'] != "" ){
						$mediainfo[$key]['caption'] = $value['caption'];
					}else{
						$mediainfo[$key]['caption'] = "";
					}

					if( $value['likes'] != "" ){
						$mediainfo[$key]['likes'] = $value['likes'];
					}else{
						$mediainfo[$key]['likes'] = "";
					}
					if( $value['views'] != "" ){
						$mediainfo[$key]['views'] = $value['views'];
					}else{
						$mediainfo[$key]['views'] = "";
					}
					if( $value['goldstar'] != "" ){
						$mediainfo[$key]['goldstar'] = $value['goldstar'];
					}else{
						$mediainfo[$key]['goldstar'] = "";
					}
					$mediainfo[$key]["media_description"] = "";
					$mediainfo[$key]['link'] = "";
					
					$mediainfo[$key]['lat'] = $value['lat'];
					$mediainfo[$key]['long'] = $value['long'];
					$mediainfo[$key]['address'] = $value['address'];
					$mediainfo[$key]['Report']=$value['report'];
					
                    $user = $this->model_name->selectRecordById("hoo_users", $value["user_id"], "id");
                    $mediainfo[$key]["username"] = $user["first_name"]." ".$user["last_name"];
					if( $user["profile_image"] != "" ){
						$mediainfo[$key]["profile_image"] = $user["profile_image"];
					}else{
						$mediainfo[$key]["profile_image"] = "";
					}
                    
                    $mediainfo[$key]["city"] = $user["city"];

                    $like_where = array('user_id' => $user_id, "media_id" => $value["media_id"]);
                    $likes = $this->model_name->selectRecordByFields('hoo_likes', $like_where);
                    if ($likes)
                        $mediainfo[$key]["is_user_liked"] = (int)$likes["is_liked"];
                    else
                        $mediainfo[$key]["is_user_liked"] = (int)0;

                    $view_where = array('user_id' => $user_id, "media_id" => $value["id"]);
                    $views = $this->model_name->selectRecordByFields('hoo_views', $view_where);
                    if ($views)
                        $mediainfo[$key]["is_user_viewed"] = (int)1;
                    else
                        $mediainfo[$key]["is_user_viewed"] = (int)0;

                    $tag_where = array("media_id" => $value["id"]);
                    $tags = $this->model_name->select_data_by_condition('hoo_tags', $tag_where, '*', '', '', '', '', array());
                    $tagged_user_ids = "";
                    $tagged_user_names = "";
                    foreach ($tags as $tag) {
                        $user = $this->model_name->selectRecordById("hoo_users", $tag["user_id"], "id");
                        if ($user) {
                            /*$tagged_user_ids .= $tag["user_id"].",";
                            $tagged_user_names .= $user["first_name"]." ".$user["last_name"].",";*/
							
							$tagged_user_id[] = $tag['user_id'];
							$tagged_user_name[] = $user['first_name'] . " " . $user['last_name'];
                        }
                    }
					$tagged_user_ids = implode([],$tagged_user_id);
					// $tagged_user_ids = ($tagged_user_id);
					$tagged_user_names = implode([],$tagged_user_name);
					// $tagged_user_names = ($tagged_user_name);
                    $mediainfo[$key]["tagged_user_ids"] = $tagged_user_ids;
                    $mediainfo[$key]["tagged_user_names"] = $tagged_user_names;
					$mediainfo[$key]["list_type"] = "Media";
					
                }
				
				
				/* Advertisement  */
				
				$count = 0;
				
				$condition  = array('is_delete' => '0','userid' => $user_id,'status' => '0');
				
				$advertisements = $this->model_name->select_data_by_condition('hoo_advertisment', $condition, 'id,userid,title,description,media,type,link,status' ,'', '', '', '', array());
				
				if( !empty( $advertisements ) ){
					
					foreach( $advertisements as $key => $value){
						
						$useradv[$key]['id'] = $value['id'];
						if( $advertisements[$key]['type'] == 0 ){
							$useradv[$key]['type'] = 'Image';
							
							$image_file_path = $this->config->item('upload_path_image');
							$useradv[$key]['media_thumbnail'] = base_url() . $image_file_path . $value['media'];
							$useradv[$key]['media_url'] = base_url() . $image_file_path . $value['media'];
							
						}else{
							$useradv[$key]['type'] = 'Video';
							$video_file_path = $this->config->item('upload_path_video');
							$useradv[$key]['media_thumbnail'] = base_url() .  $video_file_path . $value['media'];
							$useradv[$key]['media_url'] = base_url() .  $video_file_path . $value['media'];
						}
						$useradv[$key]["media_name"] = "";
						$useradv[$key]["media_extension"] = "";
						if( $advertisements[$key]["title"] != "" ){
							$useradv[$key]["caption"] = $value['title'];
						}else{
							$useradv[$key]["caption"] = "";
						}
						
						if( $advertisements[$key]["description"] != "" ){
							$useradv[$key]["media_description"] = $value['description'];
						}else{
							$useradv[$key]["media_description"] = "";
						}
						
						$useradv[$key]["likes"] = 0;
						$useradv[$key]["views"] = 0;
						$useradv[$key]['link'] = $value['link'];
						$useradv[$key]['status'] = $value['status'];
						
						$user = $this->model_name->selectRecordById("hoo_users", $value["userid"], "id");
						$useradv[$key]["username"] = $user["first_name"]." ".$user["last_name"];
						if( $user["profile_image"] != "" ){
							$useradv[$key]["profile_image"] = $user["profile_image"];
						}else{
							$useradv[$key]["profile_image"] = "";
						}
						
						$useradv[$key]["city"] = $user["city"];

						$useradv[$key]["is_user_liked"] = (int)0;
						$useradv[$key]["is_user_liked"] = (int)$likes["is_liked"];

						$useradv[$key]["is_user_viewed"] = (int)0;

						$useradv[$key]["tagged_user_ids"] = "";
						$useradv[$key]["tagged_user_names"] = "";
						$useradv[$key]["list_type"] = "Advertisement";
					
						
					}
					
				}
				
				if( !empty( $useradv ) ){
					$media = array_merge_recursive( $mediainfo , $useradv );
				}else{
					$media = $mediainfo;
				}

                $response = array(
                    'status' => 'Success', 
                    'errorcode' => '0', 
                    'msg' => 'List Media and Advertisement',
                    'mediadata' => $media,
					//'advertisement' => $usersad
                );
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'No media found for this user.'
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