<?php

class Cart extends AccModel
{
	/**
	 * The followings are the available columns in table 'post':
	 * @var integer $id
	 * @var string $session_id
	 * @var integer $product_id
	 * @var integer $quantity
	 */

	// Columns that are not set here will not be saved
	public $id;
	public $session_id;
	public $product_id;
	public $quantity;

	public $product = array();// Contains products

	/**
	 * @return string the associated database table name
	 */
	protected function tableName()
	{
		return 'cart';
	}

	// Returns the amount of products in the cart
	public static function getCartAmount(){
		$model = new Cart;
		$chartModels = $model->findAllByAttributes(array('session_id' => session_id()));
		$pModel = new Product;
		$totalPrice = 0;
		foreach($chartModels as $chartModel){
			$product = $pModel->findById($chartModel->product_id);
			$totalPrice += $chartModel->quantity * $product->price;
		}
		return $totalPrice;
	}

	// Returns the cart
	public function getCart(){
		return $this->findAllByAttributes(array('session_id' => session_id()));
	}

	public function save(){
		$attributes = array(
			'id' => $this->id,
			'session_id' => $this->session_id,
			'product_id' => $this->product_id,
			'quantity' => $this->quantity,
		);

		if($this->id){
			// We have an ID, calling the update
			return parent::update($attributes);
		}
		else{
			// We have no id, we should create a new model
			return parent::create($attributes);
		}
	}

	public function findAllByAttributes($attributes){
		$models = parent::findAllByAttributes($attributes);
		$models = $this->afterFind($models);
		return $models;
	}

	/**
	 * Performs some actions after the models have been found
	 */
	private function afterFind($models){
		
		$pModel = new Product;
		$totalPrice = 0;
		$changedModels = array();
		foreach($models as $model){
			$product = $pModel->findById($model->product_id);
			$product->stock -= $model->quantity;// Remove the cart quantity from available stock
			$model->product = $product;
			$changedModels[] = $model;
		}

		return $changedModels;
	}
}