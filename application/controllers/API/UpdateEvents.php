<?php
/*
API Name: UpdateEvents
Parameter: deviceToken, deviceType, userid, eventid, event_name, event_date, event_lat, event_long, event_city, event_invitees, event_type
Description: API will update event details
*/

require 'BaseApi.php';

class UpdateEvents extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $userid = $this->input->post('userid');
            $event_id = $this->input->post('eventid');
            if($this->isUserExists($userid)) {
                $data = array(
                    "event_name" => $this->input->post('event_name'),
                    "event_date"=> $this->input->post('event_date'),
                    "event_lat" => $this->input->post('event_lat'),
                    "event_long" => $this->input->post('event_long'),
                    "event_city" => $this->input->post('event_city'),
                    "event_invitees" => $this->input->post('event_invitees'),
                    "event_type" => $this->input->post('event_type'),
					"currency_id" => $this->input->post('currency_id'),
                    "price" => $this->input->post('price'),
                    "additional_info" => $this->input->post('additional_info'),
                    "updated_at" => date('Y-m-d h:i:s'),
                );

                if ($this->model_name->update_data($data, 'hoo_events', 'id', $event_id)) {
                    $event = $this->model_name->select_data_by_condition('hoo_events', array('id' => $event_id), '*', '', '', '', '', array());
                    $response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Event updated successfully', 'data' => $event);
                }
                else {
                    $response = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Event not updated.');
                }
            }
            else {
                $response = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'UserId is incorrect');
            }
        }
        else {
            $response = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Device configurations are not proper');
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }  
}

?>