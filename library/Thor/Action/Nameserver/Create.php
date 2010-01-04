<?php
class Thor_Action_Nameserver_Create extends Thor_Action_Abstract {
	protected $_nsInterface;
	
	/**
	 * Initialize nameserver interface
	 * @see library/Thor/Action/Thor_Action_Abstract#init()
	 * @return void
	 */
	public function init() {
		$this->_nsInterface = new Thor_Interface_Nameserver($this->log);
	}

	/**
	 * Check if all requirements are met before executing the action
	 * @see library/Thor/Action/Thor_Action_Abstract#prepare()
	 * @return bool
	 */
	public function prepare() {
		$allTestsOk = $this->_nsInterface->checkRequirements();
		
		if($allTestsOk) {
			// @TODO Check here if a domainname is available in this record
		}
		
		return $allTestsOk;
	}
	
	public function process() {
		try {
			$domain = 'blabla.nl';
			$this->log->put(sprintf('Creating zone file for %s', $domain), Thor_Log::THOR_LOG_TYPE_DEBUG);
			$this->_nsInterface->create($domain);
			return $this->success();
		} catch (Exception $e) {
			$this->log->put(sprintf($e->getMessage()), Thor_Log::THOR_LOG_TYPE_ERROR);
			return $this->error();
		}
		
		/*
		echo 'Processing ' . __CLASS__;
		
		try {
			$host = 'appsoluut.com';
			$mxRecords = $this->_nsInterface->check($host, 'mx');
			$this->log->put(sprintf('MX records for %s are: %s', $host, print_r($mxRecords, 1)), Thor_Log::THOR_LOG_TYPE_DEBUG);
			return $this->success();
		} catch (Exception $e) {
			$this->log->put(sprintf($e->getMessage()), Thor_Log::THOR_LOG_TYPE_ERROR);
			return $this->error();
		}
		*/
	}
}