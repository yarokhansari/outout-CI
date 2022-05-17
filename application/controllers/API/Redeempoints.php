<?php
/*
User: Ismail 
API Name: Like Post
Parameter: deviceToken, operation
Description: API will register user details and send email to user on registered email address
*/

require 'BaseApi.php';

class Redeempoints extends BaseApi {

  function index() {
    $response = array();


    
    // if($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
      $user_id = $this->input->post('user_id');
      $id = $this->input->post('id');
      $rewards = $this->input->post('rewards');
      $redeemed = $this->input->post('redeemed');
    //   $allpoints = $this->input->post('totalpoints');
      $alert="You dont have points";

    //   $join_str[0] = array(
    //     'table' => 'hoo_rewardnew',
    //     'join_table_id' => 'hoo_rewardnew.id',
    //     'from_table_id' => 'hoo_reward.user_id',
    //     'type' => '',
    // );

    // $condition = array('hoo_rewardnew.id='=>$id);
     
		//Get point details 

		$pointdetails = $this->model_name->select_data_by_condition('hoo_allpoints', array('user_id' => $user_id) , '*', '','', '', '', array());
        if($pointdetails[0]['totalpoints']<$redeemed)
        {
            $response = array(
                'status' => 'error', 
                'errorcode' => '2', 
                'msg' =>  $alert
              );

              header('Content-Type: application/json');
              echo json_encode($response);
              exit;
        }
        else{
        $ava=$pointdetails[0]['totalpoints']-$redeemed;
		$userpoint = array(
			'user_id' => $user_id,
			'rewards' => $rewards,
			'redeemed' =>$redeemed,
			'totalpoints' => $pointdetails[0]['totalpoints'],
			'AvailablePoints' => $ava,
		);

		$points = $this->model_name->insert_data_getid($userpoint, "hoo_reward");   
      //$this->updateLikesCount($media_id);

      $where = array("user_id" => $user_id);
     
      $likes = $this->model_name->select_data_by_condition('hoo_reward', $where, '*' ,'' , $orderby='id desc', '', array());
      $count = $ava;

      $message='Reward Redeem Successfully';
      $media_data = array(
        "totalpoints" => $count,
        // "updated_at" => date('Y-m-d h:i:s')
      );
      $this->model_name->update_data($media_data, "hoo_allpoints", "id", $user_id);
      $response = array(
        'status' => 'success', 
        'errorcode' => '0', 
        'msg' => $message,
        'data' => $likes
      );
    // }
    // else {
    //   $response = array(
    //     'status' => 'failure', 
    //     'errorcode' => '2',
    //     'msg' => 'Access Token is incorrect'
    //   );
    // }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
  }
}

}

?>