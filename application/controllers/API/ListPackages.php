<?php
/*
Author: Rutul Parikh
API Name: ListPackages
Parameter: apiKey
Description: API will list out all packages
*/

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class ListPackages extends REST_Controller {
	
	function index() {
		  
		  $packages = $this->model_name->select_data_by_condition('hoo_packages', array('is_delete' => '0') , 'id,name,price,duration,inapppurchase_key' ,'' , '' ,'', '', array());
		  
		  $count = 0;
		  
		  foreach( $packages as $package ){
			  
			  $info[$count]['id'] = $package['id'];
			  $info[$count]['name'] = $package['name'];
			  $info[$count]['price'] = $package['price'];
			  if( $package['duration'] == '0' ){
				  $info[$count]['duration'] = 'Per Month';
			  }else{
				  $info[$count]['duration'] = 'Per Year';
			  }
			  $info[$count]['inapppurchase_key'] = $package['inapppurchase_key'];
			  
			  $packageinfo['list'] = $info;
			  
			  $count++;
			  
		  }
		  
		  header('Content-Type: application/json');
		  $success = array('status' => 'Success', 'errorcode' => '0', 'msg' => 'Packages','data' => $packageinfo);
		  echo json_encode($success);
		  exit;
		  
	  
	}

}