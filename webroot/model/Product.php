<?php

class Product extends AccModel
{
	/**
	 * The followings are the available columns in table 'post':
	 * @var integer $id
	 * @var string $name
	 * @var string $stock
	 * @var string $price
	 * @var string $description
	 */

	// Columns that are not set here will not be saved
	public $id;
	public $name;
	public $stock;
	public $price;
	public $description;

	/**
	 * @return string the associated database table name
	 */
	protected function tableName()
	{
		return 'product';
	}

	public function save(){
		$attributes = array(
			'id' => $this->id,
			'name' => $this->name,
			'stock' => $this->stock,
			'price' => $this->price,
			'description' => $this->description,
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

	public function findAll(){
		$models = parent::findAll();
		$models = $this->afterFind($models);
		return $models;
	}

	/**
	 * Performs some actions after the models have been found
	 */
	private function afterFind($models){
		
		$cartModel = new Cart;
		$totalPrice = 0;
		$changedModels = array();
		foreach($models as $model){
			$cart = $cartModel->findAllByAttributes(array('session_id' => session_id()));
			foreach($cart as $cartItem){
				if($model->id == $cartItem->product_id){
					// The user have this item in his/hers cart
					$model->stock -= $cartItem->quantity;
				}
			}
			$changedModels[] = $model;
		}

		return $changedModels;
	}

	public function setAttributes($attributes){
		// We do not let the key be manipulated
		unset($attributes['id']);
		foreach($attributes as $key => $value){
			$this->$key = $value;
		}
	}
}	