<?php

use app\controllers\UserController;



use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$userController = new UserController($app);




// // Authentification
// $router->get('/login', [$userController, 'afficherLogin']);

// $router->post('/validateName', [$userController, 'validateName']);






 

