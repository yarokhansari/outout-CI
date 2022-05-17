<?php
/* API Name: Register
   Parameter: intUdId,deviceToken,deviceType,firstname,lastname,DOB,emailid,password,contactno,otp
   Description: API will register user details and send email to user on registered email address
 */

/*require '/var/www/html/firebase/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;*/

require(APPPATH . '/libraries/REST_Controller.php');

class Register extends REST_Controller {

  function index(){
	  
		  $this->load->library('email');
	  
		  /* Firebase database connection */
		  
		  /*$factory = (new Factory)
			->withServiceAccount('/var/www/html/firebase/outout-f7672-firebase-adminsdk-w3hnv-66213cd087.json')
			->withDatabaseUri('https://outout-f7672-default-rtdb.firebaseio.com/');
			
		  $database = $factory->createDatabase();*/
		
          /** Check whether email already exists or not */

		  $emailExists = 0;
		  $phoneExists = 0;
		  $usernameExists = 0;

          $data['email'] = $this->input->post('email');
          $data['phone_number'] = $this->input->post('phone_number');
          $data['username'] = $this->input->post('username');
          $info = $this->model_name->select_data_by_condition("hoo_users", array(), '*', '', '', '', '', array());
          foreach ($info as $key => $info_) {
              if($info_['email'] == $data['email']) {
                $emailExists = 1;
              }
              if($info_['phone_number'] == $data['phone_number']) {
                $phoneExists = 1;
              }
              if($info_['username'] == $data['username']) {
                $usernameExists = 1;
              }
          }

          /** Email already exists in database  */

          if( $emailExists == 1 ){
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'This email already exists. Please register using another email address');
            echo json_encode($error);
            exit;
          }else if( $phoneExists == 1 ){
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'This phone number already exists. Please register using another phone number');
            echo json_encode($error);
            exit;
          }else if( $usernameExists == 1 ){
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'This username already exists. Please register using another username');
            echo json_encode($error);
            exit;
          }else{
                /** '0' - 'Normal', '1' - 'Business Account', '2' - 'Premium' */
                $data['first_name'] = $this->input->post('first_name');
                $data['last_name'] = $this->input->post('last_name');
                $data['dob'] = date('Y-m-d',strtotime($this->input->post('dob')));
                $data['gender'] = $this->input->post('gender');
				$data['country_code'] = $this->input->post('country_code');
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

                //Commented By: Yogen - Basis on request by Remish
                //$data['is_vip'] = $this->input->post('is_vip');
                //$data['is_business'] = $this->input->post('is_business');

                $data['latitude'] = $this->input->post('latitude');
                $data['longitude'] = $this->input->post('longitude');
                $data['city'] = $this->input->post('city');
                $data['terms_and_conditions'] = $this->input->post('terms_and_conditions');
                $data['catid'] = $this->input->post('catid'); 

                //Added By: Yogen - Basis on request by Remish
                $data['account_type'] = $this->input->post('account_type');

                 /*$image_parts = explode(";base64,", $this->input->post('profile_image'));
                 $image_type_aux = explode("image/", $image_parts[0]);
                 $image_type = $image_type_aux[1];*/
                 $image_base64 = base64_decode($this->input->post('profile_image'));
                 $file = $this->config->item('upload_path_user') . uniqid() . '.jpg';
                 file_put_contents($file, $image_base64);
                 $image = base_url() .  $file;

                // if (!empty($_FILES['profile_image']['name'])){
                //   $config['upload_path'] = $this->config->item('upload_path_user');
                //   $config['allowed_types'] = $this->config->item('upload_users_allowed_types');
                //   $config['file_ext_tolower'] = 'TRUE';
                //   $this->load->library('upload', $config);
                //   $this->upload->initialize($config);
                    
                //   if ( ! $this->upload->do_upload('profile_image')) {
                //       header('Content-Type: application/json');
                //       $success = array('status' => 'Failure', 'errorcode' => '2', 'msg' => 'There is an issue in uploading the photo.');
                //       echo json_encode($success);
                //       exit;
                //   } else {
                //       $user_array = array('upload_data' => $this->upload->data());
                //       $name = $user_array['upload_data']['file_name'];
                    
                //   }

                // }
        

                $fullName = $this->input->post('first_name') . " " . $this->input->post('last_name');

                $submituserData = array(
                  "first_name" => $data['first_name'],
                  "last_name"=> $data['last_name'],
                  "dob" => $data['dob'],
                  "email" => $data['email'],
                  "gender" => $data['gender'],
                  "phone_number" => $data['phone_number'],
				          "country_code" => $data['country_code'],
                  "username" => $data['username'],
                  "password" => $data['password'],
                 // "is_business" => $data['is_business'],
                  //"is_vip" => $data['is_vip'],
                  "latitude" => $data['latitude'],
                  "longitude" => $data['longitude'],
                  "city" => $data['city'],
                  "profile_image" => $image,
                  "account_type" => $data['account_type'],
                  "terms_and_conditions" => $data['terms_and_conditions'],
                  "catid" => $data['catid'],
                  "created_at" => date('Y-m-d h:i:s'),
                );

                $submit_user = $this->model_name->insert_data_getid($submituserData, "hoo_users");

                if( $submit_user ){

                    /** Send OTP through SMS */

                    /** Send Email for confirmation of user */

                    $url = base_url();
                    $emailBody = file_get_contents(base_url() .'email-templates/user-verification.html');
                    $confirmationlink = base_url().'confirm/?TGhyfdgd5863dgggtr='.base64_encode($submit_user);
                    $emailBody = str_replace('<<BASEURL>>', $url, $emailBody); // Dynamic variable
                    $emailBody = str_replace('<<USERNAME>>', $fullName, $emailBody); // Dynamic variable
                    $emailBody = str_replace('<<LINK>>', $confirmationlink, $emailBody); // Dynamic variable
                    $subject = "OutOut Registration";
                    /*$headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: <harneet@watchfifaworldcup.com>' . "\r\n";

                    $sendmail  = mail($data['email'],$subject,$emailBody,$headers);*/
					
					$this->email->from('ukoutout@gmail.com', 'Outout');
					
					$this->email->to($data['email']);
					$this->email->subject($subject);
					$this->email->message($emailBody);
					
                    if( $this->email->send() ){
						
					  /* Insert data into firebase database */
					
					  /*$data = [
						'first_name' => $data['first_name'],
						'last_name' => $data['last_name'],
						'email' => $data['email'],
						'username' => $data['username'],
						'password' => $data['password'],
						'created_at' => date('Y-m-d h:i:s'),
					  ];
					
					  $table = "user/";
					  $postData = $database->getReference($table)->push( $data );*/
					  
						
                      $users = $this->model_name->select_data_by_condition('hoo_users',array('id' => $submit_user ), '*' ,'' , '' ,'', '', array());
                      header('Content-Type: application/json');
                      $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Registration successful. Please check your inbox for account verification email','data' => $users);
                      echo json_encode($success);
                      exit;

                    }else{
                      print_r($this->email->print_debugger());
                      header('Content-Type: application/json');
                      $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'There is error in sending an email');
                      echo json_encode($error);
                      exit;
                    }
					
					

                }else {
                  header('Content-Type: application/json');
                  $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'User is not registered with this email');
                  echo json_encode($error);
                  exit;
              }

          }
       
  }

}

?>