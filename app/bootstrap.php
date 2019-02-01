<?php

// Load options from .env
(new Dotenv\Dotenv(ROOT_PATH))->load();

date_default_timezone_set(getenv('APP_TIMEZONE'));

// define('ROOT_URL', getenv('APP_URL'));

NoNameZ\DB::connect(getenv('DB_HOSTNAME'), getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));

$settings = require ROOT_PATH . '/app/settings.php';

$app = new \Slim\App($settings);

// Set up dependencies
require ROOT_PATH . '/app/dependencies.php';

// Register middleware
require ROOT_PATH . '/app/middleware.php';

// Register routes
require ROOT_PATH . '/app/routes.php';

// Run app
$app->run();