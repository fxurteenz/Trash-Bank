<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require "../vendor/autoload.php";
use App\Router\RouterController;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

$AltoRouter = new AltoRouter();
new RouterController($AltoRouter);
