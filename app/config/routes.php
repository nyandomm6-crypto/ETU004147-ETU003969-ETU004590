<?php

use app\controllers\UserController;
use app\controllers\CategorieController;
use app\controllers\ObjetController;
use app\controllers\DemandeController;
use app\controllers\HistoController;


use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$userController = new UserController($app);
$categorieController = new CategorieController($app);
$objetController = new ObjetController($app);
$demandeController = new DemandeController($app);
$histoController = new HistoController($app);




// Authentification
$router->get('/login', [$userController, 'afficherLogin']);
$router->get('/logout', [$userController, 'deconnecterUser']);
$router->get('/signUp', [$userController, 'afficherSignUp']);

$router->post('/validateName', [$userController, 'validateName']);
$router->post('/validateEmail', [$userController, 'validateEmail']);
$router->post('/validatePassword', [$userController, 'validatePassword']);
$router->post('/signUp', [$userController, 'signUp']);
$router->post('/login', [$userController, 'login']);
$router->get('/logout', [$userController, 'deconnecterUser']);

//affichage
$router->get('/formCategorie', [$categorieController, 'afficherFormCategorie']);
$router->get('/listCategorie', [$categorieController, 'getListCategorie']); 
$router->get('/accueilAdmin', [$categorieController, 'showAccueilAdmin']);
$router->get('/listDemande', [$demandeController, 'getListDemande']);
$router->get('/listHisto', [$histoController, 'getListHisto']);
$router->get('/statistique', [$categorieController, 'showStatistique ']);




$router->post('/createCategorie', [$categorieController, 'createCategorie']);   
$router->post('/deleteCategorie', [$categorieController, 'deleteCategorie']);
$router->post('/updateCategorie', [$categorieController, 'updateCategorie']);


$router->get('/accueil', [$objetController, 'showAccueil']);
$router->get('/objet/new', [$objetController, 'showForm']);
$router->get('/objet/other', [$objetController, 'showListOther']);
$router->get('/objet/mine', [$objetController, 'showListMine']);
$router->get('/objet/delete/@id', [$objetController, 'delete']);
$router->get('/objet/edit/@id', [$objetController, 'showEditForm']);
$router->post('/objet/update/@id', [$objetController, 'update']);
$router->post('/objet/create', [$objetController, 'create']);
$router->get('/objet/search', [$objetController, 'search']);
$router->get('/objet/detail/@id', [$objetController, 'showDetail']);

// Demandes
$router->get('/demande/liste', [$demandeController, 'getListDemande']);
$router->post('/createDemande', [$demandeController, 'createDemande']);
$router->post('/demande/accepter', [$demandeController, 'accepterDemande']);
$router->post('/demande/refuser', [$demandeController, 'refuserDemande']);








 

