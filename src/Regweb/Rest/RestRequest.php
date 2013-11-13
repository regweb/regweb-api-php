<?php
namespace Regweb\Rest;

use Regweb\Rest\Exceptions\BadRequest;
use Regweb\Rest\Exceptions\Unauthorized;
use Regweb\Rest\Exceptions\Forbidden;
use Regweb\Rest\Exceptions\ServerError;
use Regweb\Rest\Exceptions\RestException;
/**
 * Represents a http request tailored to rest calls through curl.
 *
 */
class RestRequest {
	
	public $url;
	public $method;
	public $getParams = array();
	public $postParams = array();
	
	protected $verifySsl = true;
	
	const GET = 'GET';
	const POST = 'POST';
	
	public function __construct($url, $method = RestRequest::GET) {
		$this->url = $url;
		$this->method = $method;
	}
	
	public function setVerifySsl($verifySsl) {
		$this->verifySsl = $verifySsl;
	}
	
	/**
	 * Execute the configured request
	 * 
	 * @return \Regweb\Rest\RestResponse
	 */
	public function execute() {
		switch ($this->method) {
			case RestRequest::GET:
				$curlOpts = array(
					CURLOPT_URL => (count($this->getParams) > 0) ?
										(strpos($this->url, '?') === false) ?
											  $this->url . '?' . http_build_query($this->getParams)
											: $this->url . '&' . http_build_query($this->getParams)
										: $this->url,
					CURLOPT_RETURNTRANSFER => true
				);
				break;
			case RestRequest::POST:
				$curlOpts = array(
					CURLOPT_URL => (count($this->getParams) > 0) ?
										(strpos($this->url, '?') === false) ?
											  $this->url . '?' . http_build_query($this->getParams)
											: $this->url . '&' . http_build_query($this->getParams)
										: $this->url,
					CURLOPT_POST 			=> true,
					CURLOPT_POSTFIELDS 		=> http_build_query($this->postParams),
					CURLOPT_RETURNTRANSFER 	=> true,
					CURLOPT_SSL_VERIFYPEER 	=> false
				);
				break;
		}
		
		$curlHandle = curl_init();
		curl_setopt_array($curlHandle, $curlOpts);
		$curlResponse = curl_exec($curlHandle);
		$httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
		
		$response = new RestResponse($httpCode, json_decode($curlResponse, true), $curlResponse);
		
		// Generic error handling og errors signaled in http status code
		$statusCode = $response->statusCode;
		switch ($statusCode) {
			case 400:
				throw new BadRequest('bad_request', '', $response);
				break;
			case 401:
				throw new Unauthorized('unauthorized', '', $response);
				break;
			case 403:
				throw new Forbidden('forbidden', '', $response);
				break;
			default:
				if ($statusCode >= 400 && $statusCode < 500) {
					throw new RestException('error', '', $response);
				}
				if ($statusCode >= 500 && $statusCode < 600) {
					throw new ServerError('server_error', '', $response);
				}
		}
		
		return $response;
	}
	
}