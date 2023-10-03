<?php

namespace Controller;

use BinstaController;
use UserController;
use PostsController;

class RouterController
{
    public function invalidRequest($controllerName)
    {
        errorHandle('404', $controllerName . ' not found!');
    }

    public function makeRequest()
    {
        $params = explode("/", $_GET['params']);
        $controllerName = isset($paramsArray[0]) ? $paramsArray[0] : 'binsta';
        $className = ucfirst($controllerName) . 'Controller';
        header("X-Controller: $className");

        switch ($params[0]) {
            case 'account':
                $controller = new UserController();
                $method = $params[1] ?? 'login';
                break;
            case 'create':
                $controller = new PostsController();
                $method = $params[1] ?? 'create';
                break;
            default:
                $controller = new BinstaController();
                $method = $params[1] ?? 'index';
                break;
        }

        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            $this->invalidRequest($controllerName);
        }
    }

    public function userAuthenticate()
    {
        // account logout
        if (isset($_POST['Logout'])) {
            session_unset();
            session_destroy();
        }
    }
}