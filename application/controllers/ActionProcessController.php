<?php
class ActionProcessController extends Zend_Rest_Controller { //Zend_Controller_Action {
	protected $_action;
	
	public function init() {
		/* Initialize action controller here */
		$this->_action = new Default_Model_Action;
	}
	
	public function indexAction() {
		// Does nothing - nothing to list
		$this->getResponse()->setHttpResponseCode(400);
		$data = array(
			'code'		=> 400,
			'message'	=> 'No list action for this request',
		);
		
		$this->_helper->json($data);
	}
	
	public function getAction() {
		// Process action
		try {
			$actionId = $this->_getParam('id');
			$success = $this->_action->process($actionId);
			
			if($success) {
				$this->getResponse()->setHttpResponseCode(200);
				$data = array(
					'code'		=> 200,
					'message'	=> 'Action processed',
				);
			} else {
				$this->getResponse()->setHttpResponseCode(500);
				$data = array(
					'code'		=> 500,
					'message'	=> 'Action returned an error',
				);
			}
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