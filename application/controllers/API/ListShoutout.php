<?php
/*
Author: Yogen Jajal
API Name: Shoutout Listing
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

require 'BaseApi.php';

class ListShoutout extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $offset = $this->input->post('offset');
            $limit = $this->input->post('limit');
            $search_query = $this->input->post('search_query');

            $where = array("is_delete" => "0", "account_type" => "1");

            $start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;

                $users = $this->custom->getBusinessUsers($search_query);

                if(!empty($users)) {
                    $response = array(
                        'status' => 'Success', 
                        'errorcode' => '0', 
                        'msg' => 'List Shoutout',
                        'data' => $users
                    );
                }
                else {
                    $response = array(
                        'status' => 'Failed', 
                        'errorcode' => '1',
                        'msg' => 'No user found.'
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