<?php

use app\controllers\VilleController;




use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$villeController = new VilleController($app);





$router->get('/dashboard', [$villeController, 'showDashboard']);





