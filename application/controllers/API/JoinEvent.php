<?php
/*
API Name: Join Event
Parameter: deviceToken, deviceType, userid, event_name, event_date, event_lat, event_long, event_city, event_invitees, event_type
Description: API will enter event details
*/

require 'BaseApi.php';

class JoinEvent extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $user_id = $this->input->post('user_id');
            $event_id = $this->input->post('event_id');
            if($this->isUserExists($user_id)) {
                $join_event = array(
                    "user_id" => $user_id,
                    "event_id" => $event_id,
                    "created_at" => date('Y-m-d h:i:s'),
                );

                $join_event_id = $this->model_name->insert_data_getid($join_event, "hoo_join_event");
                $join_event = $this->model_name->select_data_by_condition('hoo_join_event', array('id' => $join_event_id), '*', '', '', '', '', array());
                if($join_event) {
                    //$response = $this->successResponse("User join the event successfully.", $join_event);
                    $response = array(
                        'status' => 'Success', 
                        'errorcode' => '0', 
                        'msg' => 'User join the event successfully',
                        'data' => $join_event
                    );
                }
                else {
                    //$response = $this->getFailedResponse("User could not able to join event.");
                    $response = array(
                        'status' => 'Failed', 
                        'errorcode' => '1', 
                        'msg' => 'User could not able to join event'
                    );
                }
            }
            else {
                //$response = $this->getUserNotFoundResponse();
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1', 
                    'msg' => 'User could not able to join event'
                );
            }
        }
        else {
            //$response = $this->getInvalidTokenResponse();
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