<?php

namespace Regweb\Rest;

class UpdateResult {
	
	public $success;
	public $errors;
	
	public function __construct($success, $errors = array()) {
		$this->success = $success;
		$this->errors = $errors;
	}
}