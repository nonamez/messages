<?php

$app->get('/', function($request, $response, $args) {
	$per_page = $this->get('per_page');

	$messages = NoNameZ\DB::getInstance()->query('SELECT * FROM `messages` LIMIT ' . $per_page)->fetchAll(PDO::FETCH_CLASS);

	return $this->renderer->render($response, 'index.phtml', compact('per_page', 'messages'));
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

	// Nuo visu XSS neissaugos, bet manau bendram supratimui uzteks...
	$data = array_map(function($value) {
		$value = strip_tags($value);
		$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

		return $value;
	}, $data);

	if (preg_match('/^[a-zA-ZĄČĘĖĮŠŲŪąčęėįšųū]{3,30}\s[a-zA-ZĄČĘĖĮŠŲŪąčęėįšųū]{3,30}$/u', $data['fullname']) == 0) {
		return $response->withStatus(422)->withJson(['fullname']);
	}

	$data['fullname'] = ucwords($data['fullname']);

	$birthdate = strtotime($data['birthdate']);

	if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $data['birthdate']) == 0 || $birthdate > time()) {
		return $response->withStatus(422)->withJson(['birthdate']);
	}

	if (array_key_exists('email', $data) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) == FALSE) {
		return $response->withStatus(422)->withJson(['email']);
	}

	$data['created_at'] = date('Y-m-d H:i:s');

	$data['message_id'] = NoNameZ\DB::getInstance()->insert('messages', $data);

	$data['birthdate'] = date('Y') - date('Y', $birthdate);

	return $response->withJson($data);
});