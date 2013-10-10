<?php
namespace Regweb\Rest\Exceptions;

class UnexpectedResponse extends RestException {
	public function __construct(	$error = 'unexpected_response',
									$description = '',
									$response = null,
									$debugData = array()) {
		
		parent::__construct($error, $description, $response, $debugData);
	}
}