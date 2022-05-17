<?php
/* API Name: Login with username and password
   Parameter: intUdId,deviceToken,username,password
   Description: API will update the user details
 */

error_reporting(1);
require(APPPATH . '/libraries/REST_Controller.php');

class Profile extends REST_Controller {

  function index(){


        /** Check Device Before Login */
      
        $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
        if( !empty($alldevices) ){

                $userid = $this->input->post('userid');

                /**  Check with username and password */
                $checkUser = $this->model_name->select_data_by_condition('hoo_users', array('id' => $userid), '*', '', '', '', '', array());
                if( !empty($checkUser) ) {

                    /** If previous image exists then delete it from folder */
					
					if( !empty( $_POST['profile_image'] ) ){
						
						 if ($this->config->item('upload_path_user') . $checkUser[0]['profile_image']) {
							  if (file_exists($this->config->item('upload_path_user') . $checkUser[0]['profile_image'])) {
										  @unlink($this->config->item('upload_path_user') . $checkUser[0]['profile_image']);
									   }
							}

						$image_base64 = base64_decode($_POST['profile_image']);
						$file = $this->config->item('upload_path_user') . uniqid() . '.jpg';
						file_put_contents($file, $image_base64);
						$image = base_url() .  $file;
						
					}else{
						$image = $checkUser[0]['profile_image'];
					}

                   

                    $update_array = array(

                        "first_name" => $this->input->post('first_name'),
                        "last_name" => $this->input->post('last_name'),
                        "dob" => date('Y-m-d',strtotime($this->input->post('dob'))),
                        "email" => $this->input->post('email'),
                        "gender" => $this->input->post('gender'),
                        "phone_number" => $this->input->post('phone_number'),
						"country_code" => $this->input->post('country_code'),
                        "latitude" => $this->input->post('latitude'),
                        "longitude" => $this->input->post('longitude'),
                        "city" => $this->input->post('city'),
                        "profile_image" => $image,
                        "account_type" => $this->input->post('account_type'),
                        "website" => $this->input->post('website'),
						"biography" => $this->input->post('biography'),
                        "package_id" => $this->input->post('package_id'),
						"account_number" => $this->input->post('account_number'),
						"bank_name" => $this->input->post('bank_name'),
						"account_holder" => $this->input->post('account_holder'),
						"bank_code" => $this->input->post('bank_code'),
						"swift_code" => $this->input->post('swift_code'),
                        "updated_at" => date('Y-m-d h:i:s'),
                    );
					

                    if( $this->model_name->update_data($update_array, 'hoo_users', 'id', $userid) ){

                      $userdetails[0] = $this->model_name->select_data_by_condition('hoo_users', array('id' => $userid), '*', '', '', '', '', array());
                      header('Content-Type: application/json');
                      $error = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'User details updated successfully', 'data' => $userdetails[0]);
                      echo json_encode($error);
                      exit;

                    }else{
                      header('Content-Type: application/json');
                      $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'User details not updated successfully');
                      echo json_encode($error);
                      exit;

                    }
                    
                }else{
                    header('Content-Type: application/json');
                    $error = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'UserId is incorrect');
                    echo json_encode($error);
                    exit;


                }
        }else{
            header('Content-Type: application/json');
            $error = array('status' => 'Failed', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
            echo json_encode($error);
            exit;
        }
   
  }

}

?>