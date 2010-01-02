<?php
class ActionController extends Zend_Rest_Controller { //Zend_Controller_Action {
	protected $_action;
	
	public function init() {
		/* Initialize action controller here */
		$this->_action = new Default_Model_Action;
	}
	
	public function indexAction() {
	}
	
	public function getAction() {
		try {
			$actionId = $this->_getParam('id');
			$data = $this->_action->process($actionId);
			
			$this->getResponse()->setHttpResponseCode(200);
			$data = array(
				'code'		=> 200,
				'message'	=> 'Action processed',
			);
		} catch(Exception $e) {
			$this->getResponse()->setHttpResponseCode(400);
			$data = array(
				'code'		=> 400,
				'message'	=> $e->getCode() . ' ' . $e->getMessage(),
			);
		}
		
		$this->_helper->json($data);
	}
	
	public function postAction() {
	}
	
	public function putAction() {
	}
	
	public function deleteAction() {
	}
}