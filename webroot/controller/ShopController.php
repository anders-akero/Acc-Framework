<?php
class ShopController extends AccController
{

	public function __construct(){
		// We start a session
		session_start();
		// Name of the folder containing views for this controller
		$this->viewFolder = 'product';
		// mainfile of the view defaults to main
//		$this->mainfile = 'main';
	}

	// Default action
	// Alias for actionList
	public function actionIndex(){
		$this->actionList();
	}

	// Lists all products
	public function actionList(){

		$pModel = new Product;
		$products = $pModel->findAll();

		$this->render(
			'list',// viewfile
			array(// Variables to be sent to the view
				'products' => $products,
			)
		);
	}

	// Allows user to edit his/hers cart
	public function actionCart(){

		$cart = new Cart;
		// Adds a product to the cart
		$cartModels = $cart->findAllByAttributes(array('session_id' => session_id()));

		$this->viewFolder = 'cart';
		$this->render(
			'edit',// viewfile
			array(// Variables to be sent to the view
				'cartModels' => $cartModels,
			)
		);
	}

	// Adds a given product to the cart
	public function actionAddToCart($id, $redir = 'list'){
		$pModel = new Product;
		$product = $pModel->findById($id);
		if(null == $product){
			throw new AccExeption(404, 'Page not found');
		}

		$cart = new Cart;
		// Adds a product to the cart
		$cartModel = $cart->findByAttributes(array(
			'session_id' => session_id(),
			'product_id' => $id,
		));
		$newCart = new Cart;
		$newCart->id = isset($cartModel->id)? $cartModel->id : null;// Sets the id if found
		$newCart->session_id = isset($cartModel->session_id)? $cartModel->session_id : session_id();
		$newCart->product_id = isset($cartModel->product_id)? $cartModel->product_id : $id;
		$newCart->quantity = isset($cartModel->quantity)? $cartModel->quantity +1 : 1;// adds one more product

		if($product->stock - $newCart->quantity < 0){
			// The user tried to add more product that we ahve on stock
			throw new AccExeption(417, 'Trying to add more of a product then store has in stock');
		}

		if($newCart->save()){
			$this->newFlash($product->name . ' was added to your cart');
		}
		else{
			$this->newFlash('Error: Failed to add ' . $product->name . ' to you cart');
		}
		// Redirects the user to the right page
		switch(strtolower($redir)){
			case 'cart':
				$this->actionCart();
				break;
			case 'list':
			default:
				$this->actionList();
		}
	}

	// Removes a given product to the cart
	public function actionRemoveFromCart($id){
		$pModel = new Product;
		$product = $pModel->findById($id);
		if(null == $product){
			throw new AccExeption(404, 'Page not found');
		}

		$cart = new Cart;
		// Adds a product to the cart
		$cartModel = $cart->findByAttributes(array(
			'session_id' => session_id(),
			'product_id' => $id,
		));
		$newCart = new Cart;
		$newCart->id = isset($cartModel->id)? $cartModel->id : null;// Sets the id if found
		$newCart->session_id = isset($cartModel->session_id)? $cartModel->session_id : session_id();
		$newCart->product_id = isset($cartModel->product_id)? $cartModel->product_id : $id;
		$newCart->quantity = isset($cartModel->quantity) && $cartModel->quantity > 0? $cartModel->quantity -1 : 0;// removes one product
		if($newCart->quantity){
			// If we still have atleast one of this product in cart
			$success = $newCart->save();
		} else {
			// Deletes this product from cart
			$success = $newCart->delete();
		}

		if($success){
			$this->newFlash('1 ' . $product->name . ' was removed from to your cart');
		}
		else{
			$this->newFlash('Error: Failed to remove ' . $product->name . ' from to your cart');
		}

		$this->actionCart();
	}
}