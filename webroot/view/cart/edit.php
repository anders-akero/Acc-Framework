<h2>
	Products in your cart
</h2>

<?php foreach($cartModels as $cart): ?>
	<hr>

	<h3>
		<?php echo $cart->quantity . 'x ' . $cart->product->name; ?>
	</h3>

	<p>
		<?php echo nl2br($cart->product->description); ?>
		<span class="price">Price: 
			<?php echo $cart->product->price * $cart->quantity; ?>
		</span>
	</p>

	<?php if($cart->product->stock > 0): ?>
		<a href="?r=shop/addToCart/<?php echo $cart->product->id; ?>/cart">
			Add one more to cart
		</a>
	<?php else: ?>
		<span class="lowStock">
			No more in stock
		</span>
	<?php endif; ?>
	<br>
	<br>
	<a href="?r=shop/removeFromCart/<?php echo $cart->product->id; ?>">
		Remove one from cart
	</a>
<?php endforeach; ?>