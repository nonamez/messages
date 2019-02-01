<?php

$app->get('/', function($request, $response, $args) {
	return $this->renderer->render($response, 'index.phtml', $args);
})->setName('index');