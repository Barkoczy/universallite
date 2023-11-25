<?php
/**
* Copyright 2023
*
* @package    Universal.lite
* @version		0.0.1
*	@access			public
* @author     Henrich Barkoczy <me@barkoczy.social>
* @see 	      https://universallite.com
* @see				https://github.com/Barkoczy/universallite
* @license    https://universallite.com/license
*/
declare(strict_types=1);

// @Autoload
require __DIR__ . '/../vendor/autoload.php';

// @Session
session_start();

// @Run
App\Kernel\Bootstrap::boot()->run();
