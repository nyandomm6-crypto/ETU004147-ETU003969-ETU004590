<?php

namespace app\controllers;
use app\models\DonModel;
use app\models\VilleModel;
use app\models\BesoinModel;
use app\models\ProduitModel;
use Flight;
use flight\Engine;


class DonController
{

    protected Engine $app;
    protected VilleModel $villeModel;
    protected DonModel $donModel;
    protected ProduitModel $produitModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->villeModel = new VilleModel();
        $this->donModel = new DonModel();
        $this->produitModel = new ProduitModel();
    }

    public function showFormDon()
    {
        $villes = $this->villeModel->getAllVille();
        $produits = $this->produitModel->getAllProduits();
        $this->app->render('formDon.php', [
            'base_url' => Flight::get('flight.base_url'),
            'villes' => $villes,
            'produits' => $produits
        ]);
    }

        public function createDon(array $data)
    {
        $donnee = [
            'id_ville' => $data['id_ville'],
            'id_produit' => $data['id_produit'],
            'quantite' => $data['quantite']
        ];

        $ok = $this->objet->createBesoin($donnee);

    }


}
