<?php
/*
Author: Rutul Parikh
API Name: ListCurrency 
Parameter: apiKey
Description: API will list out all currency.
*/

require 'BaseApi.php';

class ListCurrency extends BaseApi {
    function index() {
      $response = array();

	  $condition = array('is_delete' => '0');

	  $currencyinfo = $this->model_name->select_data_by_condition('hoo_currency', $condition, 'id,name,code,symbol', 'id', 'desc', '', '', array());
	  if(!empty($currencyinfo)) {
		  
			$response = array(
				'status' => 'success', 
				'errorcode' => '0', 
				'msg' => 'List Currency',
				'data' => $currencyinfo
			);
		}
		else {
			$response = array(
				'status' => 'failure', 
				'errorcode' => '1',
				'msg' => 'No currency found.'
			);
		}

      header('Content-Type: application/json');
      echo json_encode($response);
      exit;

  }
}

?>