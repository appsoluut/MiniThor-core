<?php
class IndexController extends Zend_Controller_Action {
	public function init() {
		/* Initialize action controller here */
	}
	
	public function indexAction() {
		// action body
		$action = new Default_Model_Action;
		$action->process(1);
		exit;
	}
}