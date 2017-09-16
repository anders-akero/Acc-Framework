<h2>
	Lists all products
</h2>

<?php foreach($products as $product): ?>
	<hr>

	<h3>
		<?php echo $product->name; ?>
	</h3>

	<p>
		<?php echo nl2br($product->description); ?>
		<span class="price">Price: 
			<?php echo $product->price; ?>
		</span>
	</p>

	<?php if($product->stock > 0): ?>
		<a href="?r=shop/addToCart/<?php echo $product->id; ?>">
			Add to cart
		</a>
	<?php else: ?>
		<span class="lowStock">
			Out of stock
		</span>
	<?php endif; ?>
<?php endforeach; ?>