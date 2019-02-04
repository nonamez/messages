<?php

$app->get('/', function($request, $response, $args) {

	return $this->renderer->render($response, 'index.phtml', $args);
})->setName('index');

$app->post('/comments/submit', function($request, $response) {
	$_REQUIRED = ['fullname', 'birthdate', 'message'];
	$_REQUIRED = array_flip($_REQUIRED);

	$data = $request->getParams();
	$data = array_filter($data);

	$required = array_keys(array_diff_key($_REQUIRED, $data));

	if (count($required) > 0) {
 		return $response->withStatus(422)->withJson($required);
	}

	if (preg_match('/^\w{3,30}\s\w{3,30}$/', $data['fullname']) == 0) {
		return $response->withStatus(422)->withJson(['fullname']);
	}

	$birthdate = strtotime($data['birthdate']);

	if ($birthdate == FALSE || $birthdate > time()) {
		return $response->withStatus(422)->withJson(['birthdate']);
	}

	if (array_key_exists('email', $data) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) == FALSE) {
		return $response->withStatus(422)->withJson(['email']);
	}

	
	dd($request->getParams());
});