<?php
/*
API Name: CreateEvent
Parameter: deviceToken, deviceType, userid, event_name, event_date, event_lat, event_long, event_city, event_invitees, event_type
Description: API will enter event details
*/

require 'BaseApi.php';

class CreateEvent extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $userid = $this->input->post('userid');
            // $image_base64 = base64_decode($this->input->post('image'));
            // $image_base64 =($this->input->post('image'));
            // $file = $this->config->item('upload_path_user') . uniqid() . '.jpg';
            // file_put_contents($file, $image_base64);
            // $image = base_url() .  $file;


            $image_base64 = base64_decode($this->input->post('image'));
            $file = $this->config->item('upload_path_user') . uniqid() . '.jpg';
            file_put_contents($file, $image_base64);
            $image = base_url() .  $file;
            $preorder = $this->input->post('preorder');
            $preorder_time = $this->input->post('preorder_time');
            if($this->isUserExists($userid)) {
                $submiteventData = array(
                    "userid" => $userid,
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
                    "image" => $image,
                    "preorder"=>$preorder,
                    "preorder_time"=>$preorder_time,
                    "created_at" => date('Y-m-d h:i:s'),
                );
                

                $event_id = $this->model_name->insert_data_getid($submiteventData, "hoo_events");
                $event = $this->model_name->select_data_by_condition('hoo_events', array('id' => $event_id), '*', '', '', '', '', array());
                if($event) {
                    $response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Event is registered successfully', 'data' => $event);
                }
                else {
                    $response = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Event is not registered successfully');
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