<?php

$acc = dirname(__FILE__).'/../framework/Acc.php';
$configFile = dirname(__FILE__).'/config/main.php';

require_once($acc);

Acc::setWebroot(dirname(__FILE__));
Acc::run($configFile);