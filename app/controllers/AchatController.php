<?php

namespace app\controllers;
use app\models\DonModel;
use app\models\BesoinModel;
use app\models\ProduitModel;
use Flight;
use flight\Engine;


class AchatController
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

    public function showFormAchat()
    {
        $this->app->render('formAchat', [
            'base_url' => Flight::get('flight.base_url')
        ]);
    }

    public function createPrixAchat()
    {
        $data = Flight::request()->data->getData();

        $id_produit = $data['id_produit'];
        $quantite_achats = $data['quantite_achats'];
        $frais_achats = $data['frais_achats'];
        $prix_unitaire = $this->produitModel->getPrixUnitaireByIdProduit($id_produit);
        $prixTotalAchat = $prix_unitaire * $quantite_achats + ($prix_unitaire * $quantite_achats * $frais_achats / 100);
        Flight::json($prixTotalAchat);
    }






}
