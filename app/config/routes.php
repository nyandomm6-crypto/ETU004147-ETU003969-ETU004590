<?php

use app\controllers\BesoinController;

use app\controllers\DispatchController;
use app\controllers\VilleController;
use app\controllers\DonController;
use app\controllers\AchatController;





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
$achatController = new AchatController($app);

$router->get('/formBesoin', [$BesoinController, 'showFormBesoin']);
$router->get('/besoin', [$BesoinController, 'showListBesoin']);
$router->get('/', [$villeController, 'showDashboard']);
$router->get('/showFormDon', [$donController, 'showFormDon']);
$router->get('/showFormDispatch', [$dispatchController, 'showFormDispatch']);
$router->get('/listBesoinRestant', [$villeController, 'getListBesoinRestant']);
$router->get('/showTableauRecap', [$dispatchController, 'showTableauRecapitulatif']);
$router->get('/showTableauRecap/json', [$villeController, 'getRecapitulatifJson']);
$router->get('/showListDon', [$donController, 'showListDon']);
$router->get('/showListBesoin', [$BesoinController, 'showListBesoin']);







$router->post('/showFormAchat', [$achatController, 'showFormAchat']);
$router->post('/formulaireBesoin', [$BesoinController, 'createBesoinController']);
$router->post('/createDon', [$donController, 'createDon']);
$router->post('/dispatch/create', [$dispatchController, 'createDispatch']);


$router->post('/dispatch/getInfoByProduit', [$dispatchController, 'getInfoByProduit']);
$router->post('/prixEstimatif', [$achatController, 'createPrixAchat']);
$router->post('/validateAchat', [$achatController, 'validateAchat']);
$router->get('/getDonArgent', [$achatController, 'getDonArgent']);

$router->post('/renitialiser', [$villeController, 'renitialiser']);








