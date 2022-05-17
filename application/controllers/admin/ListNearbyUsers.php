<?php
/*
API Name: ListNearbyUsers
Parameter: intUdId,deviceToken,deviceType,userid
Description: API will list out all event details
*/

require 'BaseApi.php';

class ListNearbyUsers extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $account_type = $this->input->post('account_type');
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            $search_query = $this->input->post('search_query');
            $limit = $this->input->post('limit');
            $offset = $this->input->post('offset');

            $start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;

            $radius = 1;
            $events = $this->custom->getNearbyUsers($latitude, $longitude, $radius, $account_type, $search_query, $start_index, $limit);

            if(!empty($events)) {
                $response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Event Details', 'data' => $events);
            }
            else {
                $response = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'No such event found.');
            }
        }
        else {
            $response = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

?>