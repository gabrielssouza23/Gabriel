<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$_POST = json_decode(file_get_contents('php://input'), true);


if (!defined('DB_HOST')) {
  // define('DB_HOST', '10.0.2.217');
  define('DB_HOST', 'localhost');
}

if (!defined('DB_USER')) {
  // define("DB_USER", 'auth-service');
  define("DB_USER", 'root');
}

if (!defined('DB_PASSWORD')) {
  define("DB_PASSWORD", '');
}

if (!defined('DB_NAME')) {
  define("DB_NAME", 'teste');
}
