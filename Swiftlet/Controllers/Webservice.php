<?php

namespace Swiftlet\Controllers;

/**
 * Generic webservice controller
 * @author Malitta Nanayakkara <malitta@gmail.com>
 */
class Webservice extends \Swiftlet\Controller
{
	/**
	 * Whether the webservice was executed successfully
	 */
	protected $success = true;

	/**
	 * Return array if successful
	 */
	protected $result = array();

	/**
	 * Error message if not successful
	 */
	protected $errorMessage = "An error occurred calling web service";

	/**
	 * To track execution time
	 */
	public $startTime = 0;
	
	/**
	 * To set webservice as unsuccessful
	 */
	protected function fail($message = ''){
		$this->success = false;
		$this->errorMessage = (!empty($message)) ? $message : $this->errorMessage;
		$this->returnResponse();
	}

	public function returnResponse(){
    	$execTime = microtime(true) - $this->startTime;

    	$result = array('success' => $this->success);

    	if($this->success){
    		$result['result'] = $this->result;
    		$result['exec_time'] = round($execTime, 2);
    	}else{
    		$result['error'] = $this->errorMessage;
    	}

    	header('Content-type: application/json');
		echo json_encode($result); exit;
	}
}