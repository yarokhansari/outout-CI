<?php
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Firebasemsg extends REST_Controller {

    function index() {
    						$token=array();

 	  						$alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken')), '*' ,'' , '' ,'', '', array());

                $devicedetails = $this->model_name->select_data_by_condition("hoo_devices", array(), '*' , '', '', '', '', array());

                $fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
			
			   				$notificationData = array(
			         					"title"=> "Event Added!",
			           			 "body" => "Checkout New Events Near You",
			            		 "mutable_content"=> true,
			            		 "sound"=> "Tri-tone"
			 
		                											);

			   				for($i=0;$i<=count($devicedetails);$i++)
			   				{
			   					array_push($token,$devicedetails[$i]['fcmToken']);
			   				}
                               
                $data = array(
                                'channelName' => $alldevices[0]['channelName'],
                                'notification_type' => '4',
                                'isBroadCaster' => true,
                                'hostUserId' => $userid
                            );
                            
                            $notification = $this->model_name->sendMsg(
                                $token,
                                $data,
                                $notificationData
                            );
echo json_encode($token);
			exit;

        }

}