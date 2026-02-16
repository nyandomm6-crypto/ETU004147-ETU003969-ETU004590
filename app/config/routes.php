<?php

use app\controllers\BesoinController;

use app\controllers\VilleController;
use app\controllers\DonController;




use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$villeController = new VilleController($app);
$villeController = new VilleController($app);
$BesoinController = new BesoinController($app);
$donController = new DonController($app);

$router->get('/formBesoin', [$BesoinController, 'showFormBesoin']);
$router->get('/besoin', [$BesoinController, 'showBesoin']);
$router->get('/dashboard', [$villeController, 'showDashboard']);
$router->get('/showFormDon', [$donController, 'showFormDon']);




$router->post('/formulaireBesoin', [$BesoinController, 'createBesoinController']);
$router->post('/createDon', [$donController, 'createDon']);






