<?php
/*
API Name: CreateAdvertisement
Parameter: accessToken, title, description, image, video, link, userid
Description: API will enter event details
*/

require 'BaseApi.php';

class CreateAdvertisement extends BaseApi {
    function index() {
        $response = array();
        if ($this->validateAccessToken($this->input->post('accessToken')) || 4 == 4) {
            $userid = $this->input->post('userid');
            if($this->isUserExists($userid)) {
				
				if( !empty($_FILES['file']) ){
					
					if( $this->input->post('type') == '0' ){
						
						$actual_file_path = $this->config->item('upload_path_image');
						$allowed_types = $this->config->item('upload_image_allowed_types');
						$uploadData = $this->uploadImageFile($actual_file_path, $allowed_types, uniqid(), "file");
						if ($uploadData) {
							$media_name = $uploadData["file_name"];
						}
					}else{
						
						$actual_file_path = $this->config->item('upload_path_video');
						$allowed_types = $this->config->item('upload_video_allowed_types');
						$uploadData = $this->uploadVideoFile($actual_file_path, $allowed_types, uniqid());
						if ($uploadData) {
							$media_name = $uploadData["file_name"];
						}
						
					}
				}
				
                $submitadvData = array(
                    "userid" => $userid,
                    "title" => $this->input->post('title'),
                    "description"=> $this->input->post('description'),
                    "media" => $media_name,
                    "type" => $this->input->post('type'),
                    "link" => $this->input->post('link'),
                    "created_at" => date('Y-m-d h:i:s'),
                );

                $adv_id = $this->model_name->insert_data_getid($submitadvData , "hoo_advertisment");
                $adv = $this->model_name->select_data_by_condition('hoo_advertisment', array('id' => $adv_id), '*', '', '', '', '', array());
                if($adv) {
                    $response = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Advertisement is registered successfully', 'data' => $adv);
                }
                else {
                    $response = array('status' => 'Failed', 'errorcode' => '1', 'msg' => 'Advertisement is not registered successfully');
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

?>