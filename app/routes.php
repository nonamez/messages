<?php

$app->get('/', function($request, $response, $args) {

	return $this->renderer->render($response, 'index.phtml', $args);
})->setName('index');

$app->post('/comments/submit', function($request, $response) {
	dd($request->getParams());
});