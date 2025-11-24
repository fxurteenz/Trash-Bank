<?php
require "../vendor/autoload.php";
use App\Router\RouterController;
use App\Router\RouterDispatcher;
use AltoRouter;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__,'/');
$dotenv->load();

$AltoRouter = new AltoRouter();
$RouterDispatcher = new RouterDispatcher();
new RouterController($AltoRouter,$RouterDispatcher);
