<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class EditMenuItem extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$id = $this->input->post('id'); 
		$add_time=$this->input->post('time');
		$preorder=$this->input->post('preorder');
		$userid = $this->input->post('userid');
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		$price = $this->input->post('price');
		$image_base64 = base64_decode($this->input->post('image'));
        $file = $this->config->item('upload_path_menu') . uniqid() . '.jpg';
        file_put_contents($file, $image_base64);
        $image = base_url() .  $file;
	
			
		$updatemenu = array(
			'name' => $name,
			'description' => $description,
			'price' => $price,
			'updated_at' => date('Y-m-d H:i:s'),
			'image'=>$image,
			'pre_order'=>$preorder,
			'pre_time'=>$add_time,
		);
		
		$this->model_name->update_data($updatemenu, 'hoo_food_menu', 'id', $id);
	
		header('Content-Type: application/json');
		$menuitems = $this->model_name->select_data_by_condition("hoo_food_menu", array('userid' => $userid,'is_delete' => '0','id' => $id), '*', 'id', 'DESC', '', '', array());
		$success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Item updated successfully','data' => $menuitems);
		echo json_encode($success);
		exit;
			
		
		
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}