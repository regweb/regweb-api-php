<?php
namespace Regweb\Rest\Exceptions;

class Unauthorized extends RestException {
	public function __construct(	$error = 'unauthorized',
									$description = '',
									$response = null,
									$debugData = array()) {
		
		parent::__construct($error, $description, $response, $debugData);
	}
}