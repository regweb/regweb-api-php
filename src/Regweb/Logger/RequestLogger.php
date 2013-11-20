<?php

namespace Regweb\Logger;

use Regweb\Rest\RestRequest;
use Regweb\Rest\RestResponse;
use Regweb\Rest\RequestMetaData;
class RequestLogger extends LogItem {
	
	protected $url;
	protected $apiUrl;
	protected $method;
	protected $getParams;
	protected $postParams;
	protected $verifySsl;
	
	protected $hasResponse;
	protected $statusCode;
	protected $body;
	protected $rawBody;
	
	public $logger;
	/**
	 * 
	 * @var RequestMetaData
	 */
	protected $meta;
	
	public function __construct(RestRequest $request, RestResponse $response) {
		$this->meta = $request->getMetaData();
		$this->url = $request->buildUrl();
		$this->apiUrl = $request->apiUrl;
		$this->method = $request->method;
		$this->getParams = $request->getParams;
		$this->postParams = $request->postParams;
		$this->verifySsl = $request->getVerifySsl();
		
		$this->statusCode = $response->statusCode;
		$this->body = $response->body;
		$this->rawBody = $response->getRawBody();
	}
	
	public function setLogger(Logger $logger) {
		$this->logger = $logger;
	}
	
	public function render() {
		
		$secretVars = array(
			'client_id',
			'client_secret',
			'password'
		);
		
		// Header, description, url
		$output = '<table class="request-meta">';
		$output .= '<tr><th class="header" colspan="2">';
		$output .= $this->method.' /'.$this->apiUrl.' - <em>'.$this->meta->title.'</em></th></tr>';
		$output .= '<tr><td colspan="2" class="request-description">'.$this->meta->description.'</td></tr>';
		$output .= '<tr><th class="meta-property-key">Url</th><td>'.$this->url.'</td></tr>';
		
		// Get and post params
		foreach (array('GET', 'POST') as $method) {
			$paramsMeta = ($method == 'GET') ? $this->meta->getParams : $this->meta->postParams;
			$params = ($method == 'GET') ? $this->getParams : $this->postParams;
			if (count($params) == 0) {
				continue;
			}
			
			$output .= '<tr><th class="params-subheader" colspan="2">'.$method.' parameters</th></tr>';
			foreach ($paramsMeta as $key => $description) {
				$argValue = isset($params[$key]) ? $params[$key] : null;
				if ($argValue === null) {
					$output .= '<tr class="empty"><th class="params-property-key">'.$key.'<div class="description">'.$description.'</div></th>';
					$output .= '<td>No value provided</td></tr>';
				} else {
					$output .= '<tr><th class="params-property-key">'.$key.'<div class="description">'.$description.'</div></th>';
					$output .= '<td>'.$argValue.'</td></tr>';
				}
			}
			// Also print out sent params not covered in metadata
			foreach ($params as $key => $value) {
				if (isset($paramsMeta[$key])) {
					continue;
				}
				$output .= '<tr class="unrecognized"><th class="params-property-key">'.$key.'<div class="description">Unrecognized param</div></th>';
				$output .= '<td>'.$value.'</td></tr>';
			}
		}
		
		// Responses
		$output .= '<tr><th class="responses-subheader" colspan="2">Responses by status code</th></tr>';
		foreach ($this->meta->responses as $statusCode => $response) {
			$output .= '<tr><th class="responses-property-key">'.$statusCode.'<div class="description">'.$response['description'].'</div></th><td>';
			
			if ($this->statusCode == $statusCode) {
				$output .= $this->renderResponseData($response['data'], $this->body);
				$output .= '<div class="json-response">';
				$output .= $this->rawBody;
				$output .= '</div>';
			}
			
			$output .= '</td></tr>';
		}
		
		if (!isset($this->meta->responses[$this->statusCode])) {
			$output .= '<tr class="unrecognized"><th class="responses-property-key">'.$this->statusCode.'</th><td>';
			$output .= $this->renderResponseData(array(), $this->body);
			$output .= '</td></tr>';
		}
		
		$output .= '</table>';
		return $output;
	}
	
	public function renderResponseData($data, $mappedResult = null) {
		$output = '<table class="response-table">';
		foreach ($data as $key => $description) {
			$mappedVal = isset($mappedResult[$key]) ? $mappedResult[$key] : null;
			if (is_array($description)) {
				// Child properties
				$output .= '<tr><th class="responses-property-key">'.$key.'<div class="description">'.$description['description'].'</div></th><td>';
				if (isset($description['expand'])) {
					if (!in_array($description['expand'], explode(',', $this->getParams['expand']))) {
						$output .= $this->renderResponseData($description['data'], $mappedVal);
					} else {
						$output .= $this->renderResponseData($description['expanded_data'], $mappedVal);
					}
				} else {
					$output .= $this->renderResponseData($description['data'], $mappedVal);
				}
				$output .= '</td></tr>';
				continue;
			}
			if ($mappedVal !== null) {
				$output .= '<tr><th class="responses-property-key">'.$key.'<div class="description">'.$description.'</div></th><td>';
				$output .= $mappedVal.'</td></tr>';
			} else {
				$output .= '<tr><th class="responses-property-key">'.$key.'<div class="description">'.$description.'</div></th><td>';
				$output .= '<em>Not recieved</em></td></tr>';
			}
		}
		// Print unrecognized data
		foreach ($mappedResult as $key => $value) {
			if (isset($data[$key])) {
				continue;
			}
			$output .= '<tr class="unrecognized"><th class="responses-property-key">'.$key.'<div class="description">Unrecognized data</div></th>';
			$output .= '<td>'.$value.'</td></tr>';
		}
		$output .= '</table>';
		return $output;
	}
	
	public function obfuscateVars($secretVars, $vars) {
		$filtered = array();
		foreach ($vars as $key => $value) {
			if (in_array($key, $secretVars)) {
				$filtered[$key] = '**********';
			} else {
				$filtered[$key] = $value;
			}
		}
		return $filtered;
	}
}

?>