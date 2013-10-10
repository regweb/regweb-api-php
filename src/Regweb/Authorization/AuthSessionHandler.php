<?php
namespace Regweb\Authorization;

/**
 * Simple class to represent session.
 *
 */
class AuthSessionHandler implements AuthSessionInterface {
	protected $namespace;
	
	public function __construct($sessionNamespace = '__rwa') {
		$this->namespace = $sessionNamespace;
		if (!isset($_SESSION)) {
			session_start();
		}
	}
	
	public function hasValue($name) {
		return isset($_SESSION[$this->namespace . '_' . $name]);
	}
	
	public function getValue($name) {
		return $_SESSION[$this->namespace . '_' . $name];
	}
	
	public function setValue($name, $value) {
		$_SESSION[$this->namespace . '_' . $name] = $value;
	}
	
	public function unsetKey($name) {
		unset($_SESSION[$this->namespace . '_' . $name]);
	}
	
	public function setValues($values) {
		foreach ($values as $key => $value) {
			$_SESSION[$this->namespace . '_' . $key] = $value;
		}
	}
}