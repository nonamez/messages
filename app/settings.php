<?php

return [
	'settings' => [
		'displayErrorDetails' => getenv('APP_DEBUG') == 'true' ? TRUE : FALSE,
	],

	'per_page' => 5
];