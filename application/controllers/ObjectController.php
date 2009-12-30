<?php
class ObjectController extends Zend_Rest_Controller {
	const ERRNO_NO_ID		= 10.1;
	const ERRNO_NOT_FOUND	= 10.2;
	
	const ERRMSG_NO_ID		= 'No id supplied, use /object/info/[id]';
	const ERRMSG_NOT_FOUND	= 'Supplied id doesn\'t exist';
	
	public function indexAction() {
		// TBD
		// List all objects?
		
		$pheanstalkClassRoot = APPLICATION_PATH . '/../contrib/Pheanstalk';
		require_once $pheanstalkClassRoot . '/Pheanstalk/ClassLoader.php';
		Pheanstalk_ClassLoader::register($pheanstalkClassRoot);

		$pheanstalk = new Pheanstalk('nurgle.nl');
		$id  = $pheanstalk->usetube('testtube')->put('stop');
		
		print $id;
		exit;

		/*
		while($job = $pheanstalk->watch('testtube')->ignore('default')->reserve()) {
			echo " -- " . $job->getData() . "\n";
			$pheanstalk->delete($job);
		}
		*/
		
		exit;
		
		/*
		$this->getResponse()->setHttpResponseCode(400);
		
		$data = array(
			'code'		=> self::ERRNO_NO_ID,
			'message'	=> self::ERRMSG_NO_ID,
		);
		
		$this->_helper->json($data);
		*/
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
		// Create object
		$this->getResponse()->setHttpResponseCode(201);
		
		$data = file_get_contents('php://input');
		$this->_helper->json($data);
	}
	
	public function putAction() {
		// Update object
		$this->getResponse()->setHttpResponseCode(200);
		
		print_r($data);
		
		exit;
	}
	
	public function deleteAction() {
		// Delete object
		// System will be changing the status to 'cancelled' instead of really deleting the object
	}
}