<?php
class Thor_Interface_PowerDNS extends Thor_Interface {
	/**
	 * Database driver
	 * @var Zend_Db
	 */
	protected $_db;
	
	public function init() {
		// Get database driver
		if(Zend_Registry::isRegistered('pdns_db')) {
			$this->_db = Zend_Registry::get('pdns_db');
		} else {
			$this->_db = Zend_Db::factory('Pdo_Mysql', array(
				'host'		=> 'remorse.nl',
				'username'	=> 'paul',
				'password'	=> 'paulx8726',
				'dbname'	=> 'powerdns',
			));
			$this->_db->getConnection();
			Zend_Registry::set('pdns_db', $this->_db);
		}
	}
	
	public function getDomainId($domain) {
		$domainId = false;
		$domainRow = $this->_db->fetchRow('SELECT `id` FROM `domains` WHERE `name`=?', $domain);
		
		if( !empty($domainRow) ) {
			$domainId = $domainRow['id'];
		}
		
		return $domainId;
	}
	
	public function createZone($domain) {
		if($this->getDomainId($domain) === false) {
			// Zone doesn't exist yet
			
			// Create entry in the 'domains' table
			$this->_db->insert('domains', array(
				'name'	=> $domain,
				'type'	=> 'MASTER',
			));
			$domainId = $this->_db->lastInsertId();
			
			// Create entry in the 'zones' table using the just created domain id 
			$this->_db->insert('zones', array(
				'domain_id'	=> $domainId,
				'owner'		=> 2,
				'comment'	=> 'Created by MiniThor-core',
			));
			
			$this->addRecord($domainId, $domain, 'SOA', 'ns3.remorse.nl hostmaster.' . $domain . ' 0');
			$this->addRecord($domainId, $domain, 'NS', 'ns3.remorse.nl');
			$this->addRecord($domainId, $domain, 'NS', 'ns4.remorse.nl');
			$this->addRecord($domainId, $domain, 'A', '83.96.134.196');
			$this->addRecord($domainId, 'www.'.$domain, 'A', '83.96.134.196');
			$this->addRecord($domainId, 'localhost.'.$domain, 'A', '127.0.0.1');
		} else {
			// Zone is already known by PowerDNS
			throw new Exception(sprintf('Zone for "%s" already exists', $domain));
		}
	}
	
	public function addRecord($domain, $name, $type, $content, $ttl=86400, $prio=0) {
		$domainId = $domain;
		if( !is_numeric($domain) ) {
			$domainId = $this->getDomainId($domain);
		}
		
		$this->_db->insert('records', array(
			'domain_id'		=> $domainId,
			'name'			=> $name,
			'type'			=> strtoupper($type),
			'content'		=> trim($content),
			'ttl'			=> intval($ttl),
			'prio'			=> intval($prio),
		));
	}
}