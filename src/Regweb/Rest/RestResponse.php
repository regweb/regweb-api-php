<?php
namespace Regweb\Rest;

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
	
	public function __construct($statusCode, $body, $rawBody) {
		$this->statusCode = $statusCode;
		$this->content = $body;
		$this->rawBody = $rawBody;
	}
	
	/**
	 * Returns raw content of response, should be mostly for debugging.
	 */
	public function getRawBody() {
		return $this->rawBody;
	}
}