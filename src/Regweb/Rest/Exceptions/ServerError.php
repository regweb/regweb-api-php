<?php
namespace Regweb\Rest\Exceptions;

class ServerError extends RestException {
	public function __construct(	$error = 'server_error',
									$description = '',
									$response = null,
									$debugData = array()) {
		
		parent::__construct($error, $description, $response, $debugData);
	}
}

?>