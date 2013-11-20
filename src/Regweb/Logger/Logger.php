<?php

namespace Regweb\Logger;

use Regweb\Rest\MetaData;
class Logger {
	
	protected $items;
	
	public function __construct() {
		$this->items = array();
	}
	
	public function addItem(LogItem $item) {
		$this->items[] = $item;
		$item->setLogger($this);
	}
	
	public function render() {
		$output = '';
		foreach ($this->items as $item) {
			$output .= $item->render();
		}
		return $output;
	}
}

?>