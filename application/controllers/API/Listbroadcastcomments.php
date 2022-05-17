<?php
/*
Author: Ismail Karbhari
API Name: Comments Listing for particular media
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

require 'BaseApi.php';

class Listbroadcastcomments extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            // $offset = $this->input->post('offset');
            // $limit = $this->input->post('limit');
            $user_id = $this->input->post('userid');
           
			
			
            $broadcastid = $this->input->post('broadcast_id');

            $comment_where = array("is_delete" => "0");
            if ( $broadcastid > 0)
                $comment_where["broadcast_id"] = $broadcastid;

            if ($user_id > 0)
                $comment_where["userid"] = $user_id;

            // $start_index = 0;
            // if ($offset > 0 && $limit > 0)
            //     $start_index = ($limit * $offset) - $limit;

            $commentsinfo = $this->model_name->select_data_by_condition('hoo_bcomments', $comment_where, '*', '', '', '', '', array());
			$count = 0;
			foreach( $commentsinfo as $comment ){

                // 

                
                // 
	
				// $commentlist[$count]['id'] = $comment['id'];
				$commentlist[$count]['userid'] = $comment['userid'];
				$commentlist[$count]['broadcast_id'] = $comment['broadcast_id'];
				$commentlist[$count]['image'] = $comment['image'];
				
				/* Get username based on user id */
			
				$info = $this->model_name->select_data_by_condition("hoo_users", array('id' => $comment['userid']), '*', '', '', '', '', array());
				$username = $info[0]['first_name'] . " " . $info[0]['last_name'];
			
				$commentlist[$count]['username'] = $username;
				// $commentlist[$count]['media_id'] = $comment['media_id'];
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
                    'msg' => 'List Broadcast Comments',
                    'data' => $comments
                );
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'No Broadcast Comments found.'
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