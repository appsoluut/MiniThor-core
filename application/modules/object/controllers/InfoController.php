<?php
class Object_InfoController extends Zend_Rest_Controller {
	const ERRNO_NO_ID		= 10.1;
	const ERRNO_NOT_FOUND	= 10.2;
	
	const ERRMSG_NO_ID		= 'No id supplied, use /object/info/[id]';
	const ERRMSG_NOT_FOUND	= 'Supplied id doesn\'t exist';
	
	public function indexAction() {
		$this->getResponse()->setHttpResponseCode(400);
		
		$data = array(
			'code'		=> self::ERRNO_NO_ID,
			'message'	=> self::ERRMSG_NO_ID,
		);
		
		$this->_helper->json($data);
	}
	
	public function getAction() {
		$id = $this->_getParam('id');
		
		if($id == 15) {
			$data = array(
				'id'	=> $id,
			);
		} else {
			// Simulate 404
			$this->getResponse()->setHttpResponseCode(404);
			
			$data = array(
				'code'		=> self::ERRNO_NOT_FOUND,
				'message'	=> self::ERRMSG_NOT_FOUND,
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