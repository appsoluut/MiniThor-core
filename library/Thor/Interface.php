<?php
abstract class Thor_Interface {
	/**
	 * Log object
	 * @var Thor_Log
	 */
	public $log;

	/**
	 * 
	 * @param Thor_Log $log
	 * @return unknown_type
	 */
	public function __construct(Thor_Log $log=null) {
		if($log instanceOf Thor_Log) {
			$this->log = $log;
		} else {
			$this->log = new Thor_Log;
		}
		
		$this->init();
	}
	
	public function init() {
	}
	
	public function checkRequirements() {
		return true;
	} 
}