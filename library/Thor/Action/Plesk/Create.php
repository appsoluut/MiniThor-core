<?php
class Thor_Action_Plesk_Create extends Thor_Action_Abstract {
	protected $_plesk;
	
	public function init() {
		$this->_plesk = new Thor_Interface_Plesk($this->log);
	}
	
	public function process() {
		$request = $this->_plesk->domainCreateAccount()->saveXml();
		$this->log->put("Request:\n\n" . htmlentities($request), Thor_Log::THOR_LOG_TYPE_DEBUG);
		
		$result = $this->_plesk->call($request);
		$this->log->put("Response:\n\n" . htmlentities(print_r($result, 1)) . "\n", Thor_Log::THOR_LOG_TYPE_DEBUG);
	}
}