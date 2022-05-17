<?php
/* API Name: Login with username and password
   Parameter: emailaddress, apikey
   Description: API will login
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class ForgetUserID extends REST_Controller {

  function index(){

		$this->load->library('email');
		/* Add device before login */
		
		/**  Check with username */

			$checkLogin = $this->model_name->select_data_by_condition('hoo_users', array('email' => $this->input->post('email')), '*', '', '', '', '', array());
			if( !empty($checkLogin) ){
				
				if( $checkLogin[0]['is_verified'] == '0' ){
					header('Content-Type: application/json');
					$error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Your account is not yet verified. Please check your inbox/spam folder with your registered email address Or SMS in your registered mobile');
					echo json_encode($error);
					exit;

				}else{
					
					$userid = $checkLogin[0]['id'];
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= 'From: <harneet@watchfifaworldcup.com>' . "\r\n";
					
					$username = $checkLogin[0]['username'];
					
					$base_url = base_url();
					
					//$logo=$this->config->item('common_assets_path').'images/logo/logo.jpg';
					/* Forgot Password mail Send Start */
					$body ="<div>Your username is: <b>".$username."</b>. You can use it and recover your account back.</b></div><div><br/></div>";
					$body .= "<div>Thanks,<br/> OutOut</div>";
					$emailBody = file_get_contents(base_url() . 'email-templates/forget_username.html');
					// $emailBody = str_replace('<<LOGO>>',$logo , $emailBody);
					$emailBody = str_replace('<<EMAIL_CONTENT>>', $body, $emailBody); 
					$emailBody = str_replace('<<BASEURL>>', $base_url, $emailBody);
					
					$subject = "Forget User ID";
					$to_user =  $checkLogin[0]['email'];
					
					//mail($to_user,$subject,$emailBody,$headers);
					
					$this->email->from('harneet@watchfifaworldcup.com', 'OutOut');
					
					$this->email->to($to_user);
					$this->email->subject($subject);
					$this->email->message($emailBody);
					
					if( $this->email->send() ){

						header('Content-Type: application/json');
						$error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Please check your inbox for userid');
						echo json_encode($error);
						exit;
					}else {
						header('Content-Type: application/json');
						$error = array('status' => 'Success', 'errorcode' => '1', 'msg' => 'There is some issues in sending an email');
						echo json_encode($error);
						exit;
					}


				}
				
			}else{
				header('Content-Type: application/json');
				$error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'No such email address exists in the system');
				echo json_encode($error);
				exit;

			}
			
		

      }

    }
?>