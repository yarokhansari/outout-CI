<?php
/* API Name: Payment
   Parameter: deviceToken,accessToken,userid
   Description: API will make the payment
 */


error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

require_once '/var/www/html/braintree/lib/Braintree.php';

class Payment extends REST_Controller {

  function index(){
	  
	   $alldevices = $this->model_name->select_data_by_condition('hoo_devices',array('accessToken' => $this->input->post('accessToken') ), '*' ,'' , '' ,'', '', array());
	   if( !empty($alldevices) ){
		   
			$intUdid = 	$alldevices[0]['intUdId'];
			$amount = $this->input->post('amount');
			$nonce = $this->input->post('nonce');
			
			$config = new Braintree\Configuration([
				'environment' => 'sandbox',
				'merchantId' => 'pgjg86nxzz4z4553',
				'publicKey' => 'btsh3m5gkqqwb8rj',
				'privateKey' => 'bd19b52dd0bea790cd468d35fea5ca87'
			]);
			 

			$gateway = new Braintree\Gateway($config);

			$result = $gateway->transaction()->sale([
			  'amount' => $amount,
			  'paymentMethodNonce' => $nonce,
			  'deviceData' => $intUdid,
			  'options' => [
				'submitForSettlement' => True
			  ]
			]);
			
			
	
			/* Insert data into payment table */
			
			if( $result->success == '1' ){
				
				$successdata = array(
					'transaction_id' => $result->transaction->id,
					'success' => $result->success
				);
				
				header('Content-Type: application/json');
				$success = array('status' => 'success', 'errorcode' => '0', 'msg' => 'Payment Done Sucessfully','data' => $successdata );
				echo json_encode($success);
				exit;
				
			} else {
				
				 header('Content-Type: application/json');
				 $error = array('status' => 'failure', 'errorcode' => '1', 'msg' => $result->message);
				 echo json_encode($error);
				 exit;
			}
		
		   
	   } else{
		   
		   header('Content-Type: application/json');
		   $error = array('status' => 'failure', 'errorcode' => '2', 'msg' => 'Access Token is incorrect');
		   echo json_encode($error);
		   exit;
		    
	   }
	
	  
  }
  
}

