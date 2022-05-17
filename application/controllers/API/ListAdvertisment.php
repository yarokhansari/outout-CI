<?php
/*
Author: Ismail
API Name: List Advertisement
Parameter: apiKey, userid
Description: API will list out all bookings based on userid.
*/

require 'BaseApi.php';

class ListAdvertisment extends BaseApi {
	
    function index() {
		
	  $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	  if( !empty($alldevices) ){
		  
		  $response = array();
  

          
		
		  
		//   $condition = "from_user_id = $userid AND status IN('0','1') AND is_delete = '0'";
		  $condition = "is_delete = '0'";
			  
		  $tablebooking = $this->model_name->select_data_by_condition('hoo_advertisment', $condition , '*' ,'created_at','DESC' ,'', '', array());
		  
	  
		  if( !empty( $tablebooking ) ){
			  
			  foreach( $tablebooking as $table ){
				  
				  /* package id */
			  
				  $join_str[0] = array(
					'table' => 'hoo_packages',
					'join_table_id' => 'hoo_packages.id',
					'from_table_id' => 'hoo_users.package_id',
					'type' => '',
				  );
			  
				 
				  $condition = "hoo_users.id=".$table['userid']."";
				  
				  $tableinfo = $this->model_name->select_data_by_condition('hoo_users', $condition, 'hoo_users.package_id', '', '', '', '', $join_str);

                  
				  
				  /* package name */
				  
                  $join_str[0] = array(
					'table' => 'hoo_users',
					'join_table_id' => 'hoo_users.package_id',
					'from_table_id' => 'hoo_packages.id',
					'type' => '',
				  );
			  
                
				// $condition = "hoo_packages.id=".$tableinfo['package_id']."";
                $condition = "hoo_users.id=". $table['userid']."";



				$packageinfo = $this->model_name->select_data_by_condition('hoo_packages', $condition , 'hoo_packages.name', '', '', '', '', $join_str);
              
            	  $bookinginfo['id'] = $table['id'];
				  $bookinginfo['userid'] = $table['userid'];
				  $bookinginfo['title'] = $table['title'];
				  $bookinginfo['url']=$table['link'];
				  $bookinginfo['description'] = $table['description'];
				  if($table['media']==null)
				  {
					$bookinginfo['image']=$table['media'];
				  }
				  else
				  $bookinginfo['image']="https://outout.app/uploads/images/".$table['media'];
				//   $bookinginfo['priority'] = $table['priority'];
				//   $bookinginfo['Date'] = $table['created_at'];
					$bookinginfo['packageid'] = $tableinfo[0]['package_id'];
				 	$bookinginfo['packagename'] = $packageinfo[0]['name'];
				 if($packageinfo[0]['name']=="Gold")
				 {
					$bookinginfo['Priority']=3;
				 }
				 else if($packageinfo[0]['name']=="Silver")
				 {
					$bookinginfo['Priority']=2;
				 }
				 else
				 {
					$bookinginfo['Priority']=3;

				 }
                  
                  $infodetails[] = $bookinginfo;
			  }
			  
			   header('Content-Type: application/json');
			   $response = array('status' => 'success', 'errorcode' => '0','msg' => 'Advertisment List','data' => $infodetails);
			   echo json_encode($response);
			   exit;	
			  
			 
			  
		  } else {
			  
			  header('Content-Type: application/json');
			  $response = array('status' => 'success','errorcode' => '0','msg' => 'There are no Advertising yet.');
			  echo json_encode($response);
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

?>