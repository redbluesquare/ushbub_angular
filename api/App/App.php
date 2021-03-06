<?php

namespace App;

use Joomla\Application\AbstractWebApplication;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use App\Router\AppRouter;

final class App extends AbstractWebApplication implements ContainerAwareInterface {
	protected $container;
	
	/**
	 * Character encoding string.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $charSet = 'utf-8';
	/**
	 * Response mime type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $mimeType = 'application/json';
	
	public function __construct(Container $container) {
		
		parent::__construct ();
		$this->setContainer ( $container );
		
		$config = $container->get('config');
		$this->config->merge($config);
		
		if($this->config->get('system.display_errors') == true)
		{
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}else{
			error_reporting(E_ALL);
			ini_set('display_errors', 0);
		}
		
	}
	protected function doExecute() {
		
		$router = new AppRouter($this->input, $this);
		$maps = json_decode(file_get_contents(JPATH_ROOT."/App/Config/routes.json"));
		$router->addMaps($maps, true);
		$router->setControllerPrefix('\\App');
		$router->setDefaultController('\\Controllers\\DefaultController');
		
		//fetch the controller
		$controller = $router->getController($this->get('uri.route'));
		$content = $controller->execute();
		
		$this->setBody ( json_encode($content,JSON_HEX_QUOT ) );
	}
	public function setContainer(Container $container) {
		$this->container = $container;
		return $this;
	}
	public function getContainer() {
		return $this->container;
	}
}