<?php
class ActionController extends Zend_Rest_Controller { //Zend_Controller_Action {
	protected $_action;
	
	public function init() {
		/* Initialize action controller here */
	}
	
	public function indexAction() {
		// List all actions
	}
	
	public function getAction() {
		// Get action info
		//$this->_helper->json($data);
	}
	
	public function postAction() {
		// Create new action
	}
	
	public function putAction() {
		// Update action
	}
	
	public function deleteAction() {
		// Remove action
	}
}