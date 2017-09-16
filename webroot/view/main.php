<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" type="text/css" href="css/main.css" />
	
	<script type="text/javascript" src="js/jQuery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

	<title><?php echo Acc::getAppName(); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<h1>
			<?php echo Acc::getAppName(); ?>
			<small>
				Version: <?php echo Acc::getVersion(); ?>
			</small>
		</h1>

		<a href="?r=shop/list">List all products</a>
		|
		<a href="?r=shop/cart">Edit cart</a>

		<div id="cart">
			Total in cart: <?php echo Cart::getCartAmount() ?> kr
		</div>
	</div><!-- header -->

	<div id="content">

		<?php if(isset($userFlash) && is_array($userFlash)): ?>
			<?php foreach($userFlash as $message): ?>
				<div class="flashMessage"><?php echo $message; ?></div>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php require_once($contentFile); ?>
	</div><!-- content -->

	<div id="footer">
		Created 04/2014 by Anders Åkerö.<br/>
		<?php //echo Acc::getPoweredBy(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
