<?php
namespace Regweb\Rest\Exceptions;

class Forbidden extends RestException {
	public function __construct(	$error = 'forbidden',
									$description = '',
									$response = null,
									$debugData = array()) {
		
		parent::__construct($error, $description, $response, $debugData);
	}
}