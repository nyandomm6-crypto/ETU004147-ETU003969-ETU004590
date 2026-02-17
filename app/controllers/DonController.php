<?php

namespace app\controllers;
use app\models\DonModel;
use app\models\BesoinModel;
use app\models\ProduitModel;
use Flight;
use flight\Engine;


class DonController
{

    protected Engine $app;
    protected DonModel $donModel;
    protected ProduitModel $produitModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->donModel = new DonModel();
        $this->produitModel = new ProduitModel();
    }

    public function showFormDon()
    {
        $produits = $this->produitModel->getAllProduits();
        $this->app->render('formDon.php', [
            'base_url' => Flight::get('flight.base_url'),
            'produits' => $produits
        ]);
    }

    public function createDon()
    {
        $data = Flight::request()->data->getData();

        $donnee = [
            'id_produit' => $data['id_produit'],
            'quantite' => $data['quantite']
        ];
        $this->donModel->createDon($donnee);
        $this->app->redirect('showListDon');

    }

    public function showListDon()
    {
        $dons = $this->donModel->getAllDon();

        $this->app->render('listDon.php', [
            'base_url' => Flight::get('flight.base_url'),
            'dons' => $dons,
            'produits' => $this->produitModel->getAllProduits()
        ]);
    }


}
