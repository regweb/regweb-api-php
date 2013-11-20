<?php

namespace Regweb\Logger;

class LogItem {
	
	public $item;
	public $logger;
	
	public function __construct($item) {
		$this->item = $item;
	}
	
	public function setLogger(Logger $logger) {
		$this->logger = $logger;
	}
	
	public function render() {
		return print_r($this->item, true);
	}
	
	protected function renderPropertyTable($values, $title = null) {
		$output = '<table>';
		if ($title !== null) {
			$output .= '<tr><th class="header" colspan="2">'.$title.'</th></tr>';
		}
		
		foreach ($values as $key => $value) {
			$output .= '<tr><th>'.htmlentities($key).'</th><td>';
			if (is_array($value)) {
				$output .= $this->renderPropertyTable($value);
			} else {
				$output .= $value;
			}
			$output .= '</td></tr>';
		}
		$output .= '</table>';
		return $output;
	}
}

?>