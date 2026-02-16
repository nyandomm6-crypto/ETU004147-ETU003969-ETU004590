<?php

use app\controllers\BesoinController;

use app\controllers\VilleController;



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

$villeController = new VilleController($app);
$router->get('/dashboard', [$villeController, 'showDashboard']);






 

