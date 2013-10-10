<?php
namespace Regweb\Authorization;

interface AuthSessionInterface {
	
	public function hasValue($name);
	public function getValue($name);
	public function setValue($name, $value);
	public function unsetKey($name);
	public function setValues($values);
}