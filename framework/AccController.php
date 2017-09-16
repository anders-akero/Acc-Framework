<?php
/**
 * Framework Controller, all controllers extends this one
 */
class AccController
{
	
	private $userFlash = array();
	protected $viewFolder = '';
	protected $mainfile = 'main';

	/**
	 * Renders the viewFile
	 * @param string $view name of the view-file, without .php
	 * @param array $array array containing variable to be sent to the view
	 */
	protected function render($view, $array){
		$viewPath = Acc::getWebroot() . Acc::getViewFolder();
		$mainFilePath = $viewPath;
		if($this->viewFolder){
			// The view files are located in a specific folder
			$viewPath .= $this->viewFolder . '/';
		}
		$viewFile = $viewPath . $view . '.php';

		// Checking if view file exist
		if(!is_file($viewFile)){
			throw new AccExeption(500, 'Fatal error: View "' . $view . '" not found in folder "' . $viewPath . '"');
		}

		$mainFile = $mainFilePath . $this->mainfile . '.php';
		// Checking if main file exist
		if(!is_file($mainFile)){
			throw new AccExeption(500, 'Fatal error: View "' . $mainFile . '" not found in folder "' . $mainFilePath . '"');
		}

		$array['contentFile'] = $viewFile;
		$array['userFlash'] = $this->userFlash;
		// Create variable of the array contents to be sent to the view
		foreach($array as $key => $value){
			$$key = $value;
		}

		// Renders the view-file
		require_once($mainFile);
		exit;
	}

	/**
	 * Adds a new message for the user
	 * @param string $message The message to be sent to the user
	 */
	protected function newFlash($message){
		$this->userFlash[] = $message;
	}
}