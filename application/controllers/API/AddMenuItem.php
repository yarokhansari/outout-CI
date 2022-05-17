<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AddMenuItem extends REST_Controller {

  function index(){

	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		$userid = $this->input->post('userid');
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		$price = $this->input->post('price');
		$add_time=$this->input->post('time');
		$preorder=$this->input->post('preorder');
		$image_base64 = base64_decode($this->input->post('image'));
        $file = $this->config->item('upload_path_menu') . uniqid() . '.jpg';
        file_put_contents($file, $image_base64);
        $image = base_url() .  $file;
	
	    
		$condition = 'name LIKE "' . $name . '" AND userid = "'. $userid .'" AND is_delete = "0"';
		
		$menuinfo = $this->model_name->select_data_by_condition('hoo_food_menu', $condition, '*' , '', '', '', '', array());
		
		if( !empty( $menuinfo ) ){
			
			header('Content-Type: application/json');
			$error = array('status' => 'failure', 'errorcode' => '1', 'msg' => 'This item is already added in menu');
			echo json_encode($error);
			exit;
			
		} else{
			
			$data = array(
				'userid' => $userid,
				'name' => $name,
				'description' => $description,
				'price' => $price,
				'created_at' => date('Y-m-d H:i:s'),
				'image'=>$image,
				'pre_order'=>$preorder,
				'pre_time'=>$add_time,
			);
			
			$addmenu = $this->model_name->insert_data_getid($data, "hoo_food_menu");
			
			header('Content-Type: application/json');
		    $menuitems = $this->model_name->select_data_by_condition("hoo_food_menu", array('userid' => $userid,'is_delete' => '0'), '*', 'id', 'DESC', '', '', array());
		    $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Item added successfully','data' => $menuitems);
		    echo json_encode($success);
		    exit;
			
		}
		
		
			
	  }else{
		  
		header('Content-Type: application/json');
		$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		echo json_encode($error);
		exit;
		  
	  }

		
  }

}