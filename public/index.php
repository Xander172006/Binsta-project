<?php

//autoload files
require_once '../vendor/autoload.php';
use Controller\RouterController;

//connect with database and start session
use RedBeanPHP\R as R;

session_start();
R::setup('mysql:host=localhost;dbname=binsta', 'bit_academy', 'bit_academy');

//routercontroller get request
$run = new RouterController();
$run->makeRequest();

//user authenticate, login etc
$run->userAuthenticate();