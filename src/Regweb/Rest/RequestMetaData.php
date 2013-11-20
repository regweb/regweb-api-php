<?php

namespace Regweb\Rest;

class RequestMetaData {
	
	public $title;
	public $description;
	public $url;
	public $method;
	
	const GET = 'GET';
	const POST = 'POST';
	
	/**
	 * Arguments keyed by name, with description as value
	 * @var array
	 */
	public $getParams;
	public $postParams;
	/**
	 * Array of responses keyed by status code
	 * @var array
	 */
	public $responses;
	
	public function __construct(	$title,
									$description,
									$url,
									$method,
									$getParams,
									$postParams,
									$responses) {
		$this->title = $title;
		$this->description = $description;
		$this->url = $url;
		$this->method = $method;
		$this->getParams = $getParams;
		$this->postParams = $postParams;
		$this->responses = $responses;
	}
	
	public function render() {
	
		// Header, description, url
		$output = '<table class="request-meta">';
		$output .= '<tr><th class="header" colspan="2">';
		$output .= $this->method.' /'.$this->url.' - <em>'.$this->title.'</em></th></tr>';
		$output .= '<tr><td colspan="2" class="request-description">'.$this->description.'</td></tr>';
	
		// Get and post params
		foreach (array('GET', 'POST') as $method) {
			$paramsMeta = ($method == 'GET') ? $this->getParams : $this->postParams;
			if (count($paramsMeta) == 0) {
				continue;
			}
				
			$output .= '<tr><th class="params-subheader" colspan="2">'.$method.' parameters</th></tr>';
			foreach ($paramsMeta as $key => $description) {
				$output .= '<tr><th class="params-property-key">'.$key.'</th>';
				$output .= '<td>'.$description.'</td></tr>';
			}
		}
	
		// Responses
		$output .= '<tr><th class="responses-subheader" colspan="2">Responses by status code</th></tr>';
		foreach ($this->responses as $statusCode => $response) {
			$output .= '<tr><th class="responses-property-key">'.$statusCode.'<div class="description">'.$response['description'].'</div></th><td>';
			
			$output .= $this->renderResponseData($response['data']);
			
			$output .= '</td></tr>';
		}
		$output .= '</table>';
		return $output;
	}
	
	public function renderResponseData($data, $title = null) {
		$output = '<table class="response-table">';
		if ($title !== null) {
			$output .= '<tr><th class="responses-subheader" colspan="2">'.$title.'</th></tr>';
		}
		foreach ($data as $key => $description) {
			if (is_array($description)) {
				// Child properties
				$output .= '<tr><th class="responses-property-key">'.$key.'</th><td>'.$description['description'];
				if (isset($description['expand'])) {
					$output .= $this->renderResponseData($description['data'], 'Default data');
					$output .= $this->renderResponseData($description['expanded_data'], 'Expanded data');
				} else {
					$output .= $this->renderResponseData($description['data']);
				}
				$output .= '</td></tr>';
				continue;
			}
			$output .= '<tr><th class="responses-property-key">'.$key.'</th><td>'.$description.'</td></tr>';
		}
		$output .= '</table>';
		return $output;
	}
}