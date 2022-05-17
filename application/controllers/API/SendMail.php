<?php

require(APPPATH . '/libraries/REST_Controller.php');

class SendMail extends REST_Controller {

  function index(){
	  
	    $this->load->library('email'); // Note: no $config param needed
		$this->email->from('harneet@watchfifaworldcup.com', 'Outout');
		$this->email->to('rutulp.90@gmail.com');
		$this->email->subject('Test');
		$this->email->message('This is a test.');
		$this->email->send();
	
		
  }
}