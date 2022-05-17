<?php
/*
Author: Yogen Jajal
API Name: Comments Listing for particular media
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

require 'BaseApi.php';

class ListComments extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $offset = $this->input->post('offset');
            $limit = $this->input->post('limit');
            $user_id = $this->input->post('user_id');
			
			
            $media_id = $this->input->post('media_id');

            $comment_where = array("is_delete" => "0");
            if ($media_id > 0)
                $comment_where["media_id"] = $media_id;

           /* if ($user_id > 0)
                $comment_where["user_id"] = $user_id;*/

            $start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;

            $commentsinfo = $this->model_name->select_data_by_condition('hoo_comments', $comment_where, '*', 'id', 'desc', $limit, $start_index, array());
			$count = 0;
			foreach( $commentsinfo as $comment ){
	
				$commentlist[$count]['id'] = $comment['id'];
				$commentlist[$count]['user_id'] = $comment['user_id'];
				
				/* Get username based on user id */
			
				$info = $this->model_name->select_data_by_condition("hoo_users", array('id' => $comment['user_id']), '*', '', '', '', '', array());
				$username = $info[0]['first_name'] . " " . $info[0]['last_name'];
			
				$commentlist[$count]['username'] = $username;
				$commentlist[$count]['media_id'] = $comment['media_id'];
				$commentlist[$count]['comment'] = $comment['comment'];
				$commentlist[$count]['created_at'] = $comment['created_at'];
				$commentlist[$count]['updated_at'] = $comment['updated_at'];
				$commentlist[$count]['is_delete'] = $comment['is_delete'];
				$count++;
				
				$comments['list'] = $commentlist;
				
			}

            if(!empty($comments)) {
                $response = array(
                    'status' => 'Success', 
                    'errorcode' => '0', 
                    'msg' => 'List Comments',
                    'data' => $comments
                );
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'No comments found.'
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