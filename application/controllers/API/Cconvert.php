<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');
// use Restserver\Libraries\REST_Controller;
class Cconvert extends REST_Controller {
	

  function index(){

    // $from= urlencode($from);
    // $to = urlencode($to);	

    $amount=$this->input->post('amount');
    $to=$this->input->post('to');

    $symbol = $this->model_name->select_data_by_condition("hoo_currency",array('country_code'=>$to), '' , '', '', '', '', array());
    $s=$symbol[0]['code'];

    if($s=='INR' or $s=='inr')
    {
    // $url= "http://www.google.com/search?q=".'P'."+to+".$s;
    // $get = file_get_contents($url);
    // // echo $get;
    // $data = preg_split('/\D\s(.*?)\s=\s/',$get);
    // $exhangeRate = (float) substr($data[1],0,7);
    // $convertedAmount = $amount*$exhangeRate;
    $exhangeRate = 95.99;
    $convertedAmount=$amount*$exhangeRate;
    $condition = array('code' => $s );
    $symbol = $this->model_name->select_data_by_condition("hoo_currency", $condition, 'symbol' , '', '', '', '', array());
    }

    else if($s=='USD' or $s=='usd')
    {
    $url= "http://www.google.com/search?q=".'GBP'."+to+".$s;
    $get = file_get_contents($url);
    // echo $get;
    $data = preg_split('/\D\s(.*?)\s=\s/',$get);
    $exhangeRate = (float) substr($data[1],0,7);
    $convertedAmount = $amount*$exhangeRate;
    $condition = array('code' => $s );
    $symbol = $this->model_name->select_data_by_condition("hoo_currency", $condition, 'symbol' , '', '', '', '', array());
    }
    //
    else{
      $url= "http://www.google.com/search?q=".'P'."+to+".$s;
      $get = file_get_contents($url);
      // echo $get;
      $data = preg_split('/\D\s(.*?)\s=\s/',$get);
      $exhangeRate = (float) substr($data[1],0,7);
      $convertedAmount = $amount*$exhangeRate;
      $condition = array('code' => $s );
    $symbol = $this->model_name->select_data_by_condition("hoo_currency", $condition, 'symbol' , '', '', '', '', array());
    }
   
    // return $converted_currency; 
    // 

		$data1 = array(
        'CurrentRate' => number_format((float)$exhangeRate, 2, '.', ''),
        'ConvertedAmount' =>number_format((float)$convertedAmount, 2, '.', ''),
        'Symbol'=>$symbol[0]['symbol'],
        'Currency_code'=>$s,
        // 'fromCurrency' => strtoupper($from),
        // 'toCurrency' => strtoupper($to),
		);
	
		header('Content-Type: application/json');
		$success = array('status' => 'Success', 'errorcode' => '0','currency rate'=>$data1);
		echo json_encode($success);
		exit;

		
			
    //  } else{
		  
	// 	header('Content-Type: application/json');
	// 	$error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
	// 	echo json_encode($error);
	// 	exit;
		  
	//   }

		
  }

}