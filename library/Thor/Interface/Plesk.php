<?php
class Thor_Interface_Plesk extends Thor_Interface {
	const PLESK_PACKET_VERSION	= '1.5.2.0';
	
	protected $_curl;
	
	public function checkRequirements() {
		if( !function_exists('curl_init') ) {
			throw new Exception('cURL PHP extension not installed');
		}
	}
	
	public function init() {
		$this->_host		= 'appsoluut.com';
		$this->_username	= 'admin';
		$this->_password	= 'paulx8726';
		
		$this->curlInit();
	}
	
	/**
	 * Prepares CURL to perform Plesk API request
	 * 
	 * @param string $host		Hostname
	 * @param string $login		Username
	 * @param string $password	Password
	 */
	public function curlInit() {
		$this->_curl = curl_init();
		
		curl_setopt($this->_curl, CURLOPT_URL, "https://{$this->_host}:8443/enterprise/control/agent.php");
		curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->_curl, CURLOPT_POST, TRUE);
		curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($this->_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array(
			"HTTP_AUTH_LOGIN: {$this->_username}",
			"HTTP_AUTH_PASSWD: {$this->_password}",
			"HTTP_PRETTY_PRINT: TRUE",
			"Content-type: text/html",
			"X-Request-From: Thor.Interface.Plesk8.3/" . self::PLESK_PACKET_VERSION, 
		));
	}
	
	/**
	 * Performs a Plesk API request, returns raw API response text
	 *
	 * @param string $packet		XML string conform the Plesk 8.3 API documentation
	 * @return string
	 * @throws ApiRequestException
	 */
	public function call($packet) {
		curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $packet);
		$result = curl_exec($this->_curl);
		
		if(curl_errno($this->_curl)) {
			$errMsg		= curl_error($this->_curl);
			$errCode	= curl_errno($this->_curl);
			curl_close($this->_curl);
			throw new Exception($errMsg, $errCode);			
		}
		
		curl_close($this->_curl);
		//return $result;
		
		$this->log->put("Response:\n\n" . htmlentities(print_r($result, 1)) . "\n", Thor_Log::THOR_LOG_TYPE_DEBUG);
		
		$result = $this->parseResponse($result);
		$result = $this->checkResponse($result);
		return $result;
	}
	
	public function parseResponse($responseString) {
		$xml = new SimpleXMLElement($responseString);
		if(!is_a($xml, 'SimpleXMLElement')) {
			throw new Exception('Can\'t parse server response: ' . $responseString);
		}
		
		return $xml;
	}
	
	public function checkResponse(SimpleXMLElement $response) {
		$resultNode = $response->domain->add->result;
		
		// Check if response was successful
		if('error' == (string)$resultNode->status) {
			throw new Exception('Plesk API returned error: ' . (string)$resultNode->errtext);
		}
	}
	
	public function domainCreateAccount() {
		$xmlDoc = new DOMDocument('1.0', 'UTF-8');
		$xmlDoc->formatOutput = TRUE;
		
		// packet
		$packet = $xmlDoc->createElement('packet');
		$packet->setAttribute('version', self::PLESK_PACKET_VERSION);
		$xmlDoc->appendChild($packet);
		
		// packet/domain
		$domain = $xmlDoc->createElement('domain');
		$packet->appendChild($domain);
		
		// packet/domain/add
		$add = $xmlDoc->createElement('add');
		$domain->appendChild($add);
		
		// packet/domain/add/gen_setup
		$gen_setup = $xmlDoc->createElement('gen_setup');
		$add->appendChild($gen_setup);
		
		// gen_setup elements
		$gen_setup->appendChild($xmlDoc->createElement('name', 'newdomain.com'));
		$gen_setup->appendChild($xmlDoc->createElement('client_id', 1));
		//$gen_setup->appendChild($xmlDoc->createElement('htype', 'none'));
		$gen_setup->appendChild($xmlDoc->createElement('htype', 'vrt_hst'));
		$gen_setup->appendChild($xmlDoc->createElement('ip_address', '83.96.137.226'));
		$gen_setup->appendChild($xmlDoc->createElement('status', 0));
		
		// packet/domain/add/hosting
		$hosting = $xmlDoc->createElement('hosting');
		$add->appendChild($hosting);

		/*
		$none = $xmlDoc->createElement('none');
		$hosting->appendChild($none);
		*/
		
		// packet/domain/add/hosting/vrt_hst
		$vrt_hst = $xmlDoc->createElement('vrt_hst');
		$hosting->appendChild($vrt_hst);
		
		// vrt_hst elements
		$vrt_hst->appendChild($this->_property($xmlDoc, 'ftp_login', substr(md5(microtime()), 0, 7)));
		$vrt_hst->appendChild($this->_property($xmlDoc, 'ftp_password', substr(md5(microtime()), 0, 7)));
		$vrt_hst->appendChild($xmlDoc->createElement('ip_address', '83.96.137.226'));
		
		return $xmlDoc;
	}
	
	protected function _property(DOMDocument $xmlDoc, $name, $value) {
		$property = $xmlDoc->createElement('property');
		$property->appendChild($xmlDoc->createElement('name', $name));
		$property->appendChild($xmlDoc->createElement('value', $value));
		
		return $property;
	}
}