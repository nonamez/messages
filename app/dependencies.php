<?php

$container = $app->getContainer();

$container['renderer'] = function ($c) {
	return new Slim\Views\PhpRenderer(ROOT_PATH . '/resources/templates');
};