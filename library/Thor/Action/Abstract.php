<?php
abstract class Thor_Action_Abstract {
	const ACTION_STATUS_FAILURE	= 0;
	const ACTION_STATUS_SUCCESS = 1;
	
	private $_status;
	public $log;
	
	public function __construct() {
		// Format Actionname for log to human readable format
		$action = substr(get_class($this), 12);
		$action = strtolower(str_replace('_', '.', $action));
		
		// Initialize the log for this action
		$this->log = new Thor_Log;
		$this->log->put('Log started for action ' . $action, Thor_Log::THOR_LOG_TYPE_INFO);
		
		// Initialize your own interfaces or classes
		$this->init();
	}
	
	
	public function __destroy() {
		unset($this->log);
	}
	
	/**
	 * Initialize own interfaces
	 * @return void
	 */
	public function init() {
		// * void *
	}
	
	public function prepare() {
		return TRUE;
	}
	
	public function reprocess() {
		return $this->process();
	}
	
	public function error() {
		$this->_status = self::ACTION_STATUS_FAILURE;
		return FALSE;
	}
	
	public function success() {
		$this->_status = self::ACTION_STATUS_SUCCESS;
		return TRUE;
	}
	
	public function process() {
		return $this->success();
	}
	
	public function postProcess() {
		$this->log->put('Action finished ' . ($this->_status === self::ACTION_STATUS_SUCCESS ? 'successful' : 'with an error'));
	}
	
	public function getLog() {
		return $this->log->get();
	}
}