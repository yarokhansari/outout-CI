<?php
/*
Author: Yogen Jajal
API Name: Comments Listing for particular media
Parameter: deviceToken, deviceType, userid
Description: API will list out all categories.
*/

require 'BaseApi.php';

class ListNotifications extends BaseApi {
    function index() {
        $response = array();

        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $offset = $this->input->post('offset');
            $limit = $this->input->post('limit');
            $user_id = $this->input->post('user_id');
            /*$account_type = $this->input->post('account_type');
            $is_vip = $this->input->post('is_vip');*/
			$type = $this->input->post('type');
            if(strlen($type)==1)
            $check = array("is_delete" => "0","type" =>$type);
            else
            $check="type in ($type) AND 'is_delete'=0";
            if ($user_id > 0)
                $notification_where["user_id"] = $user_id;

            $start_index = 0;
            if ($offset > 0 && $limit > 0)
                $start_index = ($limit * $offset) - $limit;
			
			$user_where = array("is_delete" => "0", 'id' => $user_id);
			
			$userdetails = $this->model_name->select_data_by_condition('hoo_users', $user_where, '*', 'id', 'desc','', '' , array());

            $notifications = $this->model_name->select_data_by_condition('hoo_notifications',  $check, '*', 'id', 'desc', $limit, $start_index, array());
         
            
            $profile = $this->model_name->select_data_by_condition('hoo_memory_media', array('id'=>$notification['media_id']), 'user_id', '', '','', '' , array());
			
			$count = 0;
			foreach($notifications as $notification){
				$notify[$count]['id'] = $notification['id'];
				$notify[$count]['user_id'] = $notification['user_id'];
                if($type == '0')
                {
                    $getid = $this->model_name->select_data_by_condition('hoo_memory_media', array('id'=>$notification['media_id']), 'user_id', '', '','', '' , array());
                    $profile = $this->model_name->select_data_by_condition('hoo_users', array('id'=>$getid[0]['user_id']), '*', '', '','', '' , array());
                    $notify[$count]['profile_image'] = $profile[0]['profile_image'];
                    
                }
                else if($user_id==null)
                {
                    $userd = $this->model_name->select_data_by_condition('hoo_users', array("id"=>$notification['user_id']), '*', 'id', 'desc','', '' , array());
                    $notify[$count]['profile_image'] = $userd[0]['profile_image'];
                }
                else
                $notify[$count]['profile_image'] = $userdetails[0]['profile_image'];

                if($type=='1')
                {
                    $notify[$count]['message_id'] = $notification['message_id'];
                }
                $notify[$count]['media_id'] = $notification['media_id'];
				$notify[$count]['description'] = $notification['description'];
				$notify[$count]['is_read'] = $notification['is_read'];
                $notify[$count]['type'] = $notification['type'];
				$notify[$count]['created_at'] = $notification['created_at'];
				$notify[$count]['updated_at'] = $notification['updated_at'];
				$count++;
			}
            //$notifications = $this->custom->getNotifications($user_id, $account_type, $is_vip);
			$data['notifications'] = $notify;
			$data['totalnotifications'] = count($notifications);

            if(!empty($notifications)) {
                /*foreach ($notifications as $key => $value) {
                    $media = $this->model_name->selectRecordByFields('hoo_memory_media', array("id" => $value["media_id"]));
                    $notifications[$key]["post"] = $media;
                }*/

                $response = array(
                    'status' => 'success', 
                    'errorcode' => '0', 
                    'msg' => 'Total Notification List',
                    'data' => $data,
                );
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'No notification found.'
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