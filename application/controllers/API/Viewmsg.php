<?php
/*
Author: Yarokh Ansari
API Name: Comments Listing for particular media
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

require 'BaseApi.php';

class Viewmsg extends BaseApi {
    function index() {
        $response = array();

        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {

            $user_id = $this->input->post('user_id');
            /*$account_type = $this->input->post('account_type');
            $is_vip = $this->input->post('is_vip');*/
			$msgid= $this->input->post('message_id');

            $where = array("user_id" => $user_id);
			$notifications = $this->model_name->select_data_by_condition('hoo_broadcast_message', $where, '*', '', '','', '' , array());

            $count = 0;
            foreach($notifications as $notification){
            $notify[$count]['id'] = $notification['id'];
         //   $profile = $this->model_name->select_data_by_condition('hoo_users', array('id'=>$notification['user_id']), '*', '', '','', '' , array());
            $userdetails = $this->model_name->select_data_by_condition("hoo_users", array('id' => $notification['to_user_id']), '*' , '', '', '', '', array());
            $like = $this->model_name->select_data_by_condition("hoo_likes", array('user_id' => $notification['user_id'],'message_id'=>$notification['id']), '*' , '', '', '', '', array());
            $notify[$count]['profile_image'] = $userdetails[0]['profile_image'];
            $notify[$count]['Name']=$userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
            $notify[$count]['Message'] = $notification['message'];
            $notify[$count]['Is_like']=$like[0]['is_liked'];
            $notify[$count]['likes']=$notification['likes'];
            $notify[$count]['from_date']=$notification['from_date'];
            $notify[$count]['to_date']=$notification['to_date'];
            $notify[$count]['offer']=$notification['is_offer'];
            $count++;
            }
            $data['notifications'] = $notify;
            if(!empty($notifications)) {
    

                $response = array(
                    'status' => 'success', 
                    'errorcode' => '0', 
                    'msg' => 'Message is',
                    'data' => $data,
                );
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'No notification found.',
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