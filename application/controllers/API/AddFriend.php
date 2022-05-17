<?php
/*
Author: Rutul Parikh
API Name: Add Friend
Parameter: deviceToken, deviceType, from_user_id, to_user_id,status
Description: API will add friend
*/

require 'BaseApi.php';

class AddFriend extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $from_user_id = $this->input->post('from_user_id');
            $to_user_id = $this->input->post('to_user_id');
            $status = $this->input->post('status');
			
            if ($this->isUserExists($from_user_id) && $this->isUserExists($to_user_id)) {
				
				$condition = '(from_user_id = '.$from_user_id.' AND to_user_id = '. $to_user_id .') OR (from_user_id = '.$to_user_id.' AND to_user_id = '. $from_user_id .')';
				
				$friendsinfo = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , '*' ,'' , '' ,'', '', array());
				
				
				if( !empty( $friendsinfo ) ){ // Record exists
				
					if( $status == '1' ){
						
						/* Update Status for from and to user id */
						
						foreach( $friendsinfo as $friend ){
							
							/* Update Status as friend request accepted */
							
							$update = array(
								'status' => '1',
								'updated_at' => date('Y-m-d h:i:s'),
							);
							
							$this->model_name->update_data($update, 'hoo_friend_request', 'id', $friend['id']);
						}
				
						
						/* Insert data into group chat table  */
					
						$userchatdata = array(
							"userid" => $to_user_id,
							"groupid" => 0,
							"adduserid"=> $from_user_id,
							"type" => '0',
							"created_at" => date('Y-m-d h:i:s'),
							"updated_at" => ''
						);
						$userchatid = $this->model_name->insert_data_getid($userchatdata, "hoo_users_chat_group");
						
						$acceptedusers = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , '*' ,'' , '' ,'', '', array());
						
						$response = array(
							'status' => 'Success', 
							'errorcode' => '0', 
							'msg' => "Friend Request Accepted",
							'data' => $acceptedusers
						);

						/* Notification */
						
						$condition = array( 'user_id' => $from_user_id );

						$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
						
						$userdetails = $this->model_name->select_data_by_condition("hoo_users", array('is_delete' => '0', 'id' => $to_user_id) , 'first_name,last_name' , '', '', '', '', array());
						$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
						
						$notificationData = array(
							  "title"=> "Friend Request Accepted",
							  "body" => $fullname ." has accepted your friend request",
							  "mutable_content"=> true,
							  "sound"=> "Tri-tone"
						);

						$data = array();
						
						$notification = $this->model_name->sendNotification(
							$devicedetails[0]['fcmToken'],
							$data,
							$notificationData
						);
						
						$addnotification = array(
							"user_id" => $from_user_id,
							"media_id" => 0,
							"description" => $fullname ." has accepted your friend request",
							"created_at" => date('Y-m-d h:i:s')
						);
						
						$fromnotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
							
					} else if ( $status == '2' ){
						
						foreach( $friendsinfo as $friend ){
							
							/* Update Status as friend request rejected */
							
							$update = array(
								'status' => '2',
								'updated_at' => date('Y-m-d h:i:s'),
							);
							
							$this->model_name->update_data($update, 'hoo_friend_request', 'id', $friend['id']);
						}
						
						$rejectedusers = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , '*' ,'' , '' ,'', '', array());
						
						/* Notification */
						
						$condition = array( 'user_id' => $from_user_id );

						$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
						
						$userdetails = $this->model_name->select_data_by_condition("hoo_users", array('is_delete' => '0', 'id' => $to_user_id) , 'first_name,last_name' , '', '', '', '', array());
						$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
						
						$notificationData = array(
							  "title"=> "Friend Request Rejected",
							  "body" => $fullname ." has rejected your friend request",
							  "mutable_content"=> true,
							  "sound"=> "Tri-tone"
						);

						$data = array();
						
						$notification = $this->model_name->sendNotification(
							$devicedetails[0]['fcmToken'],
							$data,
							$notificationData
						);
						
						$addnotification = array(
							"user_id" => $from_user_id,
							"media_id" => 0,
							"description" => $fullname ." has rejected your friend request",
							"created_at" => date('Y-m-d h:i:s')
						);
						
						$fromnotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
						
						/* Check for user chat group */
						
						$chatcondition = '(userid = '.$from_user_id.' AND adduserid = '. $to_user_id .') OR (userid = '.$to_user_id.' AND adduserid = '. $from_user_id .')';
						
						$chatusergroup = $this->model_name->select_data_by_condition('hoo_users_chat_group', $chatcondition , '*' ,'' , '' ,'', '', array());
						
						if( !empty( $chatusergroup ) ){
							
						  foreach( $chatusergroup as $chat ){
							  
							  $update = array(
								'is_delete' => '1',
								'updated_at' => date('Y-m-d h:i:s'),
							  );
							  
							  $this->model_name->update_data($update, 'hoo_users_chat_group', 'id', $chat['id']);
							  
						  }
							
						}

						$response = array(
							'status' => 'Success', 
							'errorcode' => '0', 
							'msg' => "Friend Request Rejected",
							'data' => $rejectedusers
						);	
				
						
					} else if( $status == '0' ){ // Status is cancel/pending
						
						foreach( $friendsinfo as $friend ){
							
							/* Update Status as friend request cancelled/pending */
							
							$update = array(
								'status' => '0',
								'updated_at' => date('Y-m-d h:i:s'),
							);
							
							$this->model_name->update_data($update, 'hoo_friend_request', 'id', $friend['id']);
						}
						
						$cancelusers = $this->model_name->select_data_by_condition('hoo_friend_request', $condition , '*' ,'' , '' ,'', '', array());
						
						/* Notification */
						
						$condition = array( 'user_id' => $from_user_id );

						$devicedetails = $this->model_name->select_data_by_condition("hoo_devices", $condition, '*' , '', '', '', '', array());
						
						$userdetails = $this->model_name->select_data_by_condition("hoo_users", array('is_delete' => '0', 'id' => $to_user_id) , 'first_name,last_name' , '', '', '', '', array());
						$fullname = $userdetails[0]['first_name'] . " " . $userdetails[0]['last_name'];
						
						$notificationData = array(
							  "title"=> "Friend Request Pending/Cancelled",
							  "body" => $fullname ." has rejected/cancelled your friend request",
							  "mutable_content"=> true,
							  "sound"=> "Tri-tone"
						);

						$data = array();
						
						$notification = $this->model_name->sendNotification(
							$devicedetails[0]['fcmToken'],
							$data,
							$notificationData
						);
						
						$addnotification = array(
							"user_id" => $from_user_id,
							"media_id" => 0,
							"description" => $fullname ." has rejected/cancelled your friend request",
							"created_at" => date('Y-m-d h:i:s')
						);
						
						$fromnotification = $this->model_name->insert_data_getid($addnotification, "hoo_notifications");
						
						
						/* Check for user chat group */
						
						$chatcondition = '(userid = '.$from_user_id.' AND adduserid = '. $to_user_id .') OR (userid = '.$to_user_id.' AND adduserid = '. $from_user_id .')';
						
						$chatusergroup = $this->model_name->select_data_by_condition('hoo_users_chat_group', $chatcondition , '*' ,'' , '' ,'', '', array());
						
						if( !empty( $chatusergroup ) ){
							
						  foreach( $chatusergroup as $chat ){
							  
							  $update = array(
								'is_delete' => '1',
								'updated_at' => date('Y-m-d h:i:s'),
							  );
							  
							  $this->model_name->update_data($update, 'hoo_users_chat_group', 'id', $chat['id']);
							  
						  }
							
						}
						
						$response = array(
							'status' => 'Success', 
							'errorcode' => '0', 
							'msg' => "Friend Request Cancelled / Pending",
							'data' => $cancelusers
						);	
					
					}
				
				} else{ // Record does not exists so insert the record
					
					$fromdata = array(
                        "from_user_id" => $from_user_id,
                        "to_user_id"=> $to_user_id,
                        "status" => $status,
						"send_request_date_time" => date('Y-m-d h:i:s'),
                        "created_at" => date('Y-m-d h:i:s')
                    );
					
                    $fromid = $this->model_name->insert_data_getid($fromdata, "hoo_friend_request");
					
					$todata = array(
                        "from_user_id" => $to_user_id,
                        "to_user_id"=> $from_user_id,
                        "status" => $status,
						"send_request_date_time" => date('Y-m-d h:i:s'),
                        "created_at" => date('Y-m-d h:i:s')
                    );
					
					$toid = $this->model_name->insert_data_getid($todata, "hoo_friend_request");
					
					$inserted_data = $this->model_name->select_data_by_condition('hoo_friend_request', 'id IN ('.$fromid.','.$toid.')' , '*' ,'' , '' ,'', '', array());
					$response = array(
                        'status' => 'Success', 
                        'errorcode' => '0', 
                        'msg' => "Friend Request Send",
                        'data' => $inserted_data
                    );		

					
					
				}

                
            }
            else {
                $response = array(
                    'status' => 'Failed', 
                    'errorcode' => '1',
                    'msg' => 'User does not exists.'
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