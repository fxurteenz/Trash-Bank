<?php
require "../vendor/autoload.php";
use App\Router\RouterController;
use App\Router\RouterDispatcher;
use AltoRouter;


$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

$AltoRouter = new AltoRouter();
$RouterDispatcher = new RouterDispatcher();
new RouterController($AltoRouter, $RouterDispatcher);
