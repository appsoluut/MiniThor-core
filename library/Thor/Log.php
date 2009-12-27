<?php
class Thor_Log extends Zend_Log {
	const THOR_LOG_TYPE_DEBUG		= 'debug';
	const THOR_LOG_TYPE_INFO		= 'info';
	const THOR_LOG_TYPE_WARNING		= 'warning';
	const THOR_LOG_TYPE_ERROR		= 'error';
	
	const THOR_LOG_RETURN_STACK		= 'stack';
	const THOR_LOG_RETURN_PLAIN		= 'plain';
	
	protected $_log = array();
	
	public function put($str, $type=self::THOR_LOG_TYPE_INFO) {
		// Check if a valid log type is supplied
		switch($type) {
			case self::THOR_LOG_TYPE_DEBUG:
			case self::THOR_LOG_TYPE_INFO:
			case self::THOR_LOG_TYPE_WARNING:
			case self::THOR_LOG_TYPE_ERROR:
				$inputTypeOk = TRUE;
				break;
			default:
				$inputTypeOk = FALSE;
				break;
		}
		
		if($inputTypeOk) {
			// Add the log to the stack
			$this->_log[] = array(
				'sequence'	=> count($this->_log) + 1,
				'date'		=> date('Y-m-d'),
				'time'		=> date('H:i:s'),
				'type'		=> $type,
				'details'	=> $str,
			);
		} else {
			// Invalid log type supplied, throw an exception
			throw new Exception(sprintf('Invalid log type (%s) supplied', $type));
		}
	}
	
	public function get($returnType = self::THOR_LOG_RETURN_PLAIN) {
		switch($returnType) {
			case self::THOR_LOG_RETURN_STACK:
				$log = $this->_log;
				break;
			case self::THOR_LOG_RETURN_PLAIN:
				$log = '';
				foreach($this->_log as $line) {
					$log .= sprintf("[%s %s] (%s) %s\n", $line['date'], $line['time'], strtoupper($line['type']{0}), $line['details']);
				}
				break;
			default:
				throw new Exception(sprintf('Invalid log return type (%s) supplied', $returnType));
				break;
		}
		
		return $log;
	}
}