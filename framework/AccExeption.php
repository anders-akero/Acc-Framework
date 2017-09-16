<?php
/**
 * Exception handler
 */
class AccExeption extends Exception
{
	
	/**
	 * @var integer HTTP status code
	 */
	public $statusCode;
	public $errorMessage;

	/**
	 * Constructor.
	 * @param integer $status HTTP status code
	 * @param string $message error message
	 * @param integer $code error code
	 */
	public function __construct($status, $message=null, $code=0){
		$this->statusCode = $status;
		$this->errorMessage	 = $message;

		parent::__construct(null, $code);
	}
}