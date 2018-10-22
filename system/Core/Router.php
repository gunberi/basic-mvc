<?php

namespace Sys\Core;

class Router
{
    static $routes = [];
    static public function parse($url, $request)
    {
        $url = trim($url);
        if (!$url)
        {
            $request->controller = "Home";
            $request->action = "index";
            $request->params = [];
        }
        else
        { 
            if(isset(self::$routes[$_GET['p']])) {
                $request->controller = self::$routes[$_GET['p']]['controller'];
                $request->action = self::$routes[$_GET['p']]['action'];
                $request->params = self::$routes[$_GET['p']]['params'];
            } else {
                $explode_url = explode('/', $url);
                $request->controller = $explode_url[0];
                $request->action = $explode_url[1];
                $request->params = array_slice($explode_url, 2);
            }
        }
    }

    static public function add($r, $controller, $method, $params = []) {
        self::$routes[$r] = [
            'controller' => $controller,
            'action' => $method,
            'params' => $params ?? array_slice($explode_url, 2)
        ];
    }
}