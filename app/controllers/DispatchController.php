<?php

namespace app\controllers;

use app\models\ProduitModel;
use app\models\VilleModel;
use Flight;
use flight\Engine;
use app\models\DispatchModel;
use app\models\BesoinModel;

class DispatchController
{
    protected Engine $app;

    protected DispatchModel $dispatchModel;
    protected BesoinModel $besoinModel;
    protected ProduitModel $produitModel;
    protected VilleModel $villeModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->dispatchModel = new DispatchModel();
        $this->besoinModel = new BesoinModel();
        $this->produitModel = new ProduitModel();
        $this->villeModel = new VilleModel();
    }


    public function showAllDispatch()
    {
        $data = Flight::request()->data->getData();
        $id_besoin = $data['id_besoin'];
        $dispatch = $this->dispatchModel->getDispatchId($id_besoin);
        $this->app->render('showDispatch.php', [
            'base_url' => Flight::get('flight.base_url'),
            'dispatch' => $dispatch
        ]);
    }

    public function showDispatchDetail($id_dispatch)
    {
        $dispatch = $this->dispatchModel->getDispatchDetail($id_dispatch);
        $this->app->render('showDispatchDetail', [
            'base_url' => Flight::get('flight.base_url'),
            'dispatch' => $dispatch
        ]);
    }

    public function showFormDispatch()
    {
        $this->app->render('formDispatch', [
            'error' => null,
            'succes' => null,
            'base_url' => Flight::get('flight.base_url'),
            'besoin' => $this->besoinModel->getAllBesoin(),
            'produit' => $this->produitModel->getAllProduits()

        ]);
    }

    public function createDispatch()
    {
        $data = Flight::request()->data->getData();

        $id_besoin = $data['id_besoin'];
        $quantite = $data['quantite'];
        $result = $this->dispatchModel->dispatchDon($id_besoin, $quantite);

        $this->app->redirect('/');

    }

    //json
    public function getInfoByProduit()
    {
        $data = Flight::request()->data->getData();
        $id_produit = $data['id_produit'];
        $result = $this->villeModel->getVillebyIdProduit($id_produit);
        Flight::json($result);
    }
}
