<?php
/*
Author: Yarokh Ansari
API Name: Ranking
Parameter: apiKey
Description: API will list out all accounts based on type.
*/

require 'BaseApi.php';

class Ranking extends BaseApi {
	
    function index() {

    $response = array();
	  $type = $this->input->post('type');
	  $userid = $this->input->post('userid');

	  if($type=='all'){
		$condition = " hoo_users.is_delete = '0'"; 
	  }
	else
		$condition = "hoo_users.catid = '$type' AND hoo_users.is_delete = '0'";
		  
		  $join_str[0] = array(
				'table' => 'hoo_category',
				'join_table_id' => 'hoo_category.id',
				'from_table_id' => 'hoo_users.catid',
				'type' => '',
		  );
		  
		  $info = $this->model_name->select_data_by_condition('hoo_users', $condition, 'hoo_users.id,hoo_users.first_name,hoo_users.last_name,hoo_users.username,hoo_users.email,hoo_users.profile_image,hoo_users.username,hoo_users.city,hoo_users.dob,hoo_users.gender,hoo_users.account_type,hoo_users.is_vip,hoo_category.name as catname,longitude,latitude', '', '', '', '', $join_str);

	$result=array();
		for($i=0;$i<=count($info);$i++)
		{
			$rank=$this->model_name->select_data_by_allcondition('hoo_rating', array(), 'to_user_id,SUM(rating) as Rating','','','', '', array(),'to_user_id');
		}

		//$key = array_search('100', array_column($rank, 'to_user_id'));

// 		function sortByOrder($a, $b) {
//     return $a['Rating'] - $b['Rating'];
// }

// usort($result, 'sortByOrder');


//print_r($key);

		  if(!empty($info)) {
				foreach( $info as $userinfo ){
					
					$user['user_id'] = $userinfo['id'];
                    
                    
                    $condition = array( 'is_delete' => '0','user_id' => $userinfo['id']);
					
					$userstory = $this->model_name->select_data_by_condition("hoo_user_story", $condition, 'story' , '', '', '', '', array());

					$key = array_search($userinfo['id'], array_column($rank, 'to_user_id'));
					if($key==null)
					{
                        $user['Ranking']=$key;
						
					}
					else
					{
						$user['Ranking']=$key+1;
					}
					
					$user['full_name'] = $userinfo['first_name'] . " " . $userinfo['last_name'];
					
					if( $userinfo['city'] != "" ){
						$user['city'] = $userinfo['city'];
					}else{
						$user['city'] = "";
					}
					if( $userinfo['profile_image'] != "" ){
						$user['profile_image'] = $userinfo['profile_image'];
					}else{
						$user['profile_image'] = "";
					}
                    if($userinfo['latitude']!=""){
                        $user['latitude']=$userinfo['latitude'];
                    }else{
                        $user['latitude']="";
                    }
                    if($userinfo['longitude']!=""){
                        $user['longitude']=$userinfo['longitude'];
                    }else{
                        $user['longitude']="";
                    }
					if( $userinfo['catname'] != "" ){
						$user['catname'] = $userinfo['catname'];
					}else{
						$user['catname'] = "";
					}
                    if( $userstory[0]['story'] != "" ){
                        $user['story'] = "1";
                    }else {
                        $user['story'] = "0";
                    }
					
					
					$details[] = $user;
					
				}
			  
				$response = array(
					'status' => 'success', 
					'errorcode' => '0', 
					'msg' => 'List Accounts',
					'data' => $details,
				);
			}
			else {
				$response = array(
					'status' => 'success', 
					'errorcode' => '0',
					'msg' => 'There are no accounts.'
				);
			}
		  
	 

      header('Content-Type: application/json');
      echo json_encode($response);
      exit;

  }
}

?>