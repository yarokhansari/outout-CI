<?php
/*
Author: Yogen Jajal
API Name: Invite Friends
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

require 'BaseApi.php';

class InviteFriends extends BaseApi {
    function index() {
        $response = array();
        if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $user_id = $this->input->post('user_id');
            $search_query = $this->input->post('search_query');
            $offset = $this->input->post('offset');
            $limit = $this->input->post('limit');

            $start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;

            $users = $this->custom->getMyFriends($user_id, $search_query, $start_index, $limit);

            if(!empty($users)) {
                $response = array(
                    'status' => 'Success',
                    'errorcode' => '0',
                    'msg' => 'Invite Friends',
                    'data' => $users
                );
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'No friend found.'
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