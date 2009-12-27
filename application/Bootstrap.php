<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	/**
	 * Initialize the autoloaders
	 * @return void
	 */
	protected function _initAutoload() {
		// Add Thor namespace
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Thor_');

		// Add autoloader for models in current 'application' folder
		$autoloader = new Zend_Application_Module_Autoloader(array(
			'namespace'	=> 'Default_',
			'basePath'	=> dirname(__FILE__),
		));
		return $autoloader;
	}
	
	/**
	 * Sets the doctype to XHTML 1.0 strict
	 * @return void
	 */
	protected function _initDoctype() {
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}
	
	protected function _initRestRoute() {
		$this->bootstrap('frontController');
		$frontController = Zend_Controller_Front::getInstance();
		$restRoute = new Zend_Rest_Route($frontController);
		$frontController->getRouter()->addRoute('default', $restRoute);
	}
}