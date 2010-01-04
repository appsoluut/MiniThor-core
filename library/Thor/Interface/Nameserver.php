<?php
class Thor_Interface_Nameserver extends Thor_Interface {
	public function check($host, $recordType='A') {
		$output = array();
		$command = 'dig ' . $host . ' -t' . $recordType . ' +short +time=1';
		$this->log->put(sprintf('Performing dig call: %s', $command), Thor_Log::THOR_LOG_TYPE_DEBUG);
		exec($command, &$output);
		
		if(count($output) > 0) {
			// Check for errors
			$outputString = join("\n", $output);
			if(strpos($outputString, ';;') !== FALSE) {
				/* Found a comment line; which means we have an error
				 * Get the last line for the actual error
				 */
				$errorMessage = ucfirst(substr($output[count($output) -1], 3));
				throw new Exception(sprintf('Dig command failed with error: %s', $errorMessage));
			}
			return $output;
		} else {
			throw new Exception(sprintf('Couldn\'t find and %s records for host %s', strtoupper($recordType), $host));
		}
	}
	
	public function create($domain) {
		$pdns = new Thor_Interface_PowerDNS;
		$pdns->createZone($domain);
	}
}