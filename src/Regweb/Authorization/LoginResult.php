<?php
namespace Regweb\Authorization;

/**
 * Simple class to structure the response from login attempts.
 */
class LoginResult {
	public $success;
	public $missingParams;
	public $activeCheckFailed;
	public $uniqueEmailCheckFailed;
	
	public function __construct($success, $missingParams, $activeCheckFailed, $uniqueEmailCheckFailed) {
		$this->success = $success;
		$this->missingParams = $missingParams;
		$this->activeCheckFailed = $activeCheckFailed;
		$this->uniqueEmailCheckFailed = $uniqueEmailCheckFailed;
	}
}