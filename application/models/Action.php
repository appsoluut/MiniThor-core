<?php
class Default_Model_Action extends Zend_Db_Table_Abstract {
	protected $_name = 'actions';
	
	public function process($id) {
		$action = $this->fetchRow($id);
		
		$action->script = 'plesk.create';
		
		list($group, $function) = split('\.', $action->script);
		
	    $str = 'Thor_Action_' . ucfirst(strtolower($group)) . '_' . ucfirst(strtolower($function));
		$action = new $str;
		$action->prepare();
		$action->process();
		$action->postProcess();
		
		echo '<pre>' . print_r($action->getLog(), 1) . '</pre>';
		/*
		$log = new Default_Model_Log;
		$log->insert(array('details' => $action->getLog()));
		*/
	}
}