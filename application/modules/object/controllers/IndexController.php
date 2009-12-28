<?php
class Object_IndexController extends Zend_Rest_Controller {
	public function indexAction() {
		// TBD
		// List all objects?
		
		$pheanstalkClassRoot = APPLICATION_PATH . '/../contrib/Pheanstalk';
		require_once $pheanstalkClassRoot . '/Pheanstalk/ClassLoader.php';
		Pheanstalk_ClassLoader::register($pheanstalkClassRoot);

		$pheanstalk = new Pheanstalk('192.168.2.1');
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
	}
	
	public function getAction() {
		// Get all info from object
		$id = $this->_getParam('id');
	}
	
	public function postAction() {
		// Post object
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