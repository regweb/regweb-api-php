<?php
namespace Regweb\Rest;

use Regweb\Logger\Logger;
/**
 * Represents a http response. Tailored for rest use.
 *
 */
class RestResponse {
	
	public $statusCode = null;
	/**
	 * Decoded json response
	 * @var array
	 */
	public $body;
	protected $rawBody;
	protected $logger;
	
	public function __construct($statusCode, $body, $rawBody, Logger $logger) {
		$this->statusCode = $statusCode;
		$this->body = $body;
		$this->rawBody = $rawBody;
		$this->logger = $logger;
	}
	
	/**
	 * Returns raw content of response, should be mostly for debugging.
	 */
	public function getRawBody() {
		return $this->rawBody;
	}
}