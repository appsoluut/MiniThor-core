<?php
abstract class Thor_Interface {
	public $log;
	
	public function __construct($log=null) {
		if($log instanceOf Thor_Log) {
			$this->log = $log;
		} else {
			$this->log = new Thor_Log;
		}
		
		$this->init();
	}
	
	public function init() {
	}
	
	public function checkPrequirements() {
		return TRUE;
	} 
}