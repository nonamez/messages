<?php

$app->get('/', function($request, $response, $args) {
	$per_page = $this->get('per_page');

	$total = NoNameZ\DB::getInstance()->query('SELECT COUNT(*) FROM `messages`')->fetchColumn();

	$pages = ceil($total / $per_page);

	$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, [
		'options' => array(
			'default'   => 1,
			'min_range' => 1,
			'max_range' => $pages
		),
	]);

	$offset = ($page - 1) * $per_page;

	$stmt =  NoNameZ\DB::getInstance()->prepare('SELECT * FROM `messages` ORDER BY `id` DESC LIMIT :limit OFFSET :offset');

	$stmt->bindParam(':limit', $per_page, PDO::PARAM_INT);
	$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

	$stmt->execute();

	$messages = $stmt->fetchAll(PDO::FETCH_CLASS);
 
	return $this->renderer->render($response, 'index.phtml', compact('per_page', 'messages', 'page', 'pages'));
})->setName('index');

$app->post('/', function($request, $response) {
	return $response->withRedirect($this->router->pathFor('index'));
});

$app->post('/comments/submit', function($request, $response) {
	$_REQUIRED = ['fullname', 'birthdate', 'message'];
	$_REQUIRED = array_flip($_REQUIRED);

	$data = $request->getParams();
	$data = array_filter($data);

	// Checking required field first
	$required = array_keys(array_diff_key($_REQUIRED, $data));

	if (count($required) > 0) {
		return $response->withStatus(422)->withJson($required);
	}

	// Simple XSS check, not the best, but for now will be enough
	$data = array_map(function($value) {
		$value = strip_tags($value);
		$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

		return $value;
	}, $data);

	// First and second names check
	if (preg_match('/^[a-zA-ZĄČĘĖĮŠŲŪąčęėįšųū]{3,30}\s[a-zA-ZĄČĘĖĮŠŲŪąčęėįšųū]{3,30}$/u', $data['fullname']) == 0) {
		return $response->withStatus(422)->withJson(['fullname']);
	}

	// Converting to title case
	$data['fullname'] = ucwords($data['fullname']);

	$birthdate = strtotime($data['birthdate']);

	// Birthdate format and range check
	if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $data['birthdate']) == 0 || $birthdate > time()) {
		return $response->withStatus(422)->withJson(['birthdate']);
	}

	// Email check
	if (array_key_exists('email', $data) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) == FALSE) {
		return $response->withStatus(422)->withJson(['email']);
	}

	$data['created_at'] = date('Y-m-d H:i:s');

	$data['message_id'] = NoNameZ\DB::getInstance()->insert('messages', $data);

	$data['birthdate'] = date('Y') - date('Y', $birthdate);

	return $response->withJson($data);
});