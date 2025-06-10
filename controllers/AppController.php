<?php

namespace Controllers;

use Exception;
use MVC\Router;

class AppController {
    public static function index(Router $router){
        $router->render('pages/index', []);
        //$router->render('login/index', [], 'layout/layoutLogin');

    }
}