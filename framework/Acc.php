<?php
/**
 * Acc-framework class file.
 *
 * @author Anders Åkerö <anders@akero.eu>
 * @link http://hackme.akero.eu/
 * @copyright 2014 Åkerö Webb
 * @license GNU
 * @package system
 * @since 1.0
 */

/**
 * Defines the Acc framework installation path
 */
defined('ACC_PATH') or define('ACC_PATH',dirname(__FILE__));

/**
 * Framework class
 */
class Acc
{
	// PDO object
	private static $db;

	private static $_config = array();
	private static $_webroot;

	/**
	 * @return string version of framework
	 */
	public static function getVersion(){
		return '0.1';
	}

	/**
	 * Connect to a MySQL database using PHP PDO
	 * Throw new exception when missing parameters in config file
	 */
	private static function dbConnect(){
		$config = self::$_config;
		if(!isset($config['db'])){
			throw new AccExeption(500, 'Fatal error: Undefined property: db in config file');
		}
		$db = $config['db'];
		if(!isset($db['connectionString'])){
			throw new AccExeption(500, 'Fatal error: Undefined property: connectionString in config file');
		}
		if(!isset($db['username'])){
			throw new AccExeption(500, 'Fatal error: Undefined property: username in config file');
		}
		if(!isset($db['password'])){
			throw new AccExeption(500, 'Fatal error: Undefined property: password in config file');
		}
		if(!isset($db['charset'])){
			throw new AccExeption(500, 'Fatal error: Undefined property: charset in config file');
		}

		$dsn      = $db['connectionString'];
		$options  = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . $db['charset'] . "'");

		self::$db = new PDO($dsn, $db['username'], $db['password'], $options);
	}

	/**
	 * Sets framework config-setttings
	 * @param array $config application configuration.
	 */
	private static function setConfig($config){
		self::$_config = $config;
	}

	/**
	 * Includes all php-files in model-folder
	 * Finding and include the models
	 */
	private static function initModels(){

		// Finding all models
		$path = self::getWebroot() . 'model/';
		$models = glob($path . '*.php');

		// Include the models
		foreach($models as $model){
			require_once($model);
		}
	}

	/**
	 * Getting the correct controller and action from url
	 * Initiating the asked controller
	 * Throw new exception when missing parameters in config file
	 */
	private static function initController(){
		$controller = $action = $var1 = $var2 = $var3 = '';
		if(isset($_GET['r'])){
			$ca = explode('/', $_GET['r']);
			$controller = isset($ca[0])? $ca[0] : '';
			$action = isset($ca[1])? $ca[1] : '';
			$var1 = isset($ca[2])? $ca[2] : false;
			$var2 = isset($ca[3])? $ca[3] : false;
			$var3 = isset($ca[4])? $ca[4] : false;
		}

		$cClass = self::getControllerClass($controller);

		if(!$action){
			// index is the default action if none given
			$action = 'index';
		}
		// Makes first letter in second part of the name uppercase, this is how we want the methods to be named
		$action = 'action' . ucfirst(strtolower($action));// eg. actionIndex

		// Check if given action exists
		if(!method_exists($cClass, $action)){
			// The method / action does not exist for this class / controller
			throw new AccExeption(404, 'Page not found');
		}

		// Call the given action/function/method from the class
		// Up to 3 variable is possible to send to the controller
		$cClass->$action($var1, $var2, $var3);
	}

	/**
	 * Calls for given controller and return its class
	 * @param string $controller name of the asked controller.
	 * @return object controller class
	 */
	private static function getControllerClass($controller){
		$config = self::$_config;
		if(!isset($config['defaultController'])){
			throw new AccExeption(500, 'Fatal error: Undefined property: defaultController in config file');
		}
		if(!$controller){
			// No controller given
			// Using defaultController given in configFile instead
			$controller = $config['defaultController'];
		}

		// Makes first letter uppercase, this is how we want the filename to be
		$controller = ucfirst(strtolower($controller)) . 'Controller';// eg. PostController
		$path = self::getWebroot() . '/controller/';
		$file = $controller . '.php';
		$controllerFile = $path . $file;

		if(!is_file($controllerFile)){
			// The file does not exist or is not a file
			throw new AccExeption(404, 'Page not found');
		}

		// Include the controller and call the class
		require_once($controllerFile);
		$className = $controller;
		if(!class_exists($className)){
			// Class does not exist or is misspelled, classname must be same as filename
			throw new AccExeption(500, 'Fatal error: Class \'' . $className . '\' not found in ' . $controllerFile);
		}
		// Controller Class
		$cClass = new $className;
		return $cClass;
	}

	/**
	 * @param string $configFile Path to application configuration
	 */
	public static function run($configFile){
		try {
			// Page / code to run goes here
			$config = (include $configFile);
			self::setConfig($config);
			self::dbConnect();
			self::initModels();
			self::initController();
		}
		catch (Exception $e) {
			// Error handler
			header(
				'HTTP/1.0 ' . $e->statusCode . ' ' . $e->getMessage(),
				true,
				$e->statusCode
			);
			echo '<h1>';
				echo 'Error: ';
				if(isset($e->statusCode) && $e->statusCode){
					// We have a status code from AccExeption
					echo $e->statusCode . ' ';
				}
				echo '<small>';
					if(isset($e->statusCode) && $e->statusCode){
						// Thrown to/by AccExeption
						echo $e->errorMessage;
					} else {
						// Thrown directly to exeption
						echo $e->getMessage();
					}
				echo '</small>';
			echo '</h1>';
		}
	}

	/**
	 * @return string, path to the view-folder
	 */
	public static function getViewFolder(){
		$config = self::$_config;
		if(!isset($config['viewFolder'])){
			throw new AccExeption(500, 'Fatal error: Undefined property: viewFolder in config file');
		}
		return $config['viewFolder'] . '/';
	}

	/**
	 * Sets framework webroot-folder
	 * @param string $path, Path to webroot-folder
	 */
	public static function setWebroot($path){
		self::$_webroot = $path . '/';
	}

	/**
	 * @return string a string that contain path to webroot
	 */
	public static function getWebroot(){
		return self::$_webroot;
	}

	/**
	 * @return string a string that can be displayed on Web page showing Powered-by-Acc information
	 */
	public static function getPoweredBy(){
		return 'Powered by Acc.';
	}

	/**
	 * @return string a string that contain the application name
	 */
	public static function getAppName(){
		$config = self::$_config;
		if(!isset($config['name'])){
			throw new AccExeption(500, 'Fatal error: Undefined property: name in config file');
		}
		return $config['name'];
	}

	/**
	 * @return object PDO-object
	 */
	public static function db(){
		self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		return self::$db;
	}

}

require_once(ACC_PATH . '/AccController.php');
require_once(ACC_PATH . '/AccExeption.php');
require_once(ACC_PATH . '/AccModel.php');
