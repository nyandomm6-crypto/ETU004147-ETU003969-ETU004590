<?php

use app\controllers\BesoinController;



use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$BesoinController = new BesoinController($app);





$router->get('/formBesoin', [$BesoinController, 'showFormBesoin']);

$router->get('/besoin', [$BesoinController, 'showBesoin']);

$router->post('/formulaireBesoin', [$BesoinController, 'createBesoinController']);






 

