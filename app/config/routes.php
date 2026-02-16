<?php

use app\controllers\BesoinController;

use app\controllers\DispatchController;
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
$dispatchController = new DispatchController($app);

$router->get('/formBesoin', [$BesoinController, 'showFormBesoin']);
$router->get('/besoin', [$BesoinController, 'showBesoin']);
$router->get('/dashboard', [$villeController, 'showDashboard']);
$router->get('/showFormDon', [$donController, 'showFormDon']);
$router->get('/showFormDispatch', [$dispatchController, 'showFormDispatch']);




$router->post('/formulaireBesoin', [$BesoinController, 'createBesoinController']);
$router->post('/createDon', [$donController, 'createDon']);







