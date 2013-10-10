<?php
namespace Regweb\Rest\Exceptions;

class RestException extends \Exception {
	public $error;
	public $description;
	public $response;
	protected $debugData;
	
	public function __construct(	$error,
									$description,
									$response = null,
									$debugData = array()) {
		
		parent::__construct($error);
		
		$this->error = $error;
		$this->description = $description;
		$this->response = $response;
		$this->debugData = $debugData;
	}
	
	public function getDebugData() {
		return $this->debugData;
	}
}