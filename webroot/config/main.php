<?php
// Config-file
return array(
	// Name of the project / application
	'name' => 'Arbetsprov',

	// Default controller if none given
	'defaultController' => 'Shop',

	// Path to the views
	'viewFolder' => 'view',

	// Settings for MySQL database
	'db'=>array(
		'connectionString' => 'mysql:host=localhost;dbname=webshop',
		'username'         => 'root',
		'password'         => '',
		'charset'          => 'UTF8',
	),
);