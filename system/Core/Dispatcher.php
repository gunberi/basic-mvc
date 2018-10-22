<?php

namespace Sys\Core;

use App\Model\AppRouteModel;

class Dispatcher
{
    private $request;
    public function dispatch()
    {
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);
        $controller = $this->loadController();
        call_user_func_array([$controller, $this->request->action], $this->request->params);
    }

    public function loadController()
    {
        $name = "App\\Controller\\" . ucfirst($this->request->controller) . "Controller";
        if(!class_exists($name)) {
            $name = "App\\Controller\\Error404Controller";
            $this->request->action = 'index';
        }
        $controller = new $name;
        return $controller;
    }
}