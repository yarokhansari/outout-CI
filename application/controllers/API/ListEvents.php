<?php
/*
API Name: ListEvents
Parameter: intUdId,deviceToken,deviceType,userid
Description: API will list out all event details
*/

require 'BaseApi.php';

class ListEvents extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $userid = $this->input->post('userid');
            $event_type = $this->input->post('event_type');
            $search_query = $this->input->post('search_query');
            $limit = $this->input->post('limit');
            $offset = $this->input->post('offset');

            $start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;

            $events = $this->custom->getEvents($userid, $event_type, $search_query, $start_index, $limit);

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