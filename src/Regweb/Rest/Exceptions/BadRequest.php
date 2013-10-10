<?php

namespace Regweb\Rest\Exceptions;

class BadRequest extends RestException {
	public function __construct(	$error = 'bad_request',
									$description = '',
									$response = null,
									$debugData = array()) {
		
		parent::__construct($error, $description, $response, $debugData);
	}
}