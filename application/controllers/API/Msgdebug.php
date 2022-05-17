<?php
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');
require_once "RtcTokenBuilder.php";
class Msgdebug extends REST_Controller {

    function index() {

        $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken')), '*' ,'' , '' ,'', '', array());

        $response = array();
        if (!empty($alldevices) ) {
            $userid = $this->input->post('userid');
            $tagged=$this->input->post('event_invitees');
            if(!empty($userid)) {
                $submiteventData = array(
                    "userid" => $userid,
                    "event_name" => $this->input->post('event_name'),
                    "event_date"=> $this->input->post('event_date'),
                    "event_lat" => $this->input->post('event_lat'),
                    "event_long" => $this->input->post('event_long'),
                    "event_city" => $this->input->post('event_city'),
                    "event_invitees" => $tagged,
                    "event_type" => $this->input->post('event_type'),
					"currency_id" => $this->input->post('currency_id'),
                    "price" => $this->input->post('price'),
                    "additional_info" => $this->input->post('additional_info'),
                    "created_at" => date('Y-m-d h:i:s'),
                );

                $condition = array( 'user_id' => $tagged );
                $devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
                $userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $userid, 'is_delete' => '0'), '*' , '', '', '', '', array());
                $frienddetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $friendid, 'is_delete' => '0'), '*' , '', '', '', '', array());


                $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
			
			    $notificationData = array(
			         "title"=> "New Event Added!",
			            "body" => "$fullname Tagged You in Event",
			             "mutable_content"=> true,
			             "sound"=> "Tri-tone"
			 
		                	);
                            $data = array(
                                'channelName' => $alldevices[0]['channelName'],
                                'notification_type' => '4',
                                'isBroadCaster' => true,
                                'hostUserId' => $userid
                            );
                            
                            $notification = $this->model_name->sendNotification(
                                $devicedetails[0]['fcmToken'],
                                $data,
                                $notificationData
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