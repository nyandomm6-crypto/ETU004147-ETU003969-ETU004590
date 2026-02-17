<?php

namespace app\controllers;
use app\models\AchatModel;
use app\models\DispatchModel;
use app\models\DonModel;
use app\models\BesoinModel;
use app\models\ProduitModel;
use app\models\VilleModel;
use Flight;
use flight\Engine;


class AchatController
{

    protected Engine $app;
    protected DonModel $donModel;
    protected ProduitModel $produitModel;
    protected AchatModel $achatModel;

    protected VilleModel $villeModel;

    protected DispatchModel $dispatchModel;

    protected BesoinModel $besoinModel;


    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->donModel = new DonModel();
        $this->produitModel = new ProduitModel();
        $this->achatModel = new AchatModel();
        $this->villeModel = new VilleModel();
        $this->dispatchModel = new DispatchModel();
        $this->besoinModel = new BesoinModel();
    }

    public function showFormAchat()
    {
        $data = Flight::request()->data->getData();

        //verification si dont existe

        $id_produit = $data['id_produit'];
        if ($this->donModel->getResteDonMateriel($id_produit) == 0) {
            $this->app->render('formAchat', [
                'base_url' => Flight::get('flight.base_url'),
                'id_produit' => $data['id_produit'],
                'id_ville' => $data['id_ville']
            ]);
        } else {
            $besoins = $this->villeModel->getResteBesoinParProduitParVille();
            $this->app->render('listBesoinRestant.php', [
                'error' => 'don mbola misy',
                'base_url' => Flight::get('flight.base_url'),
                'besoins' => $besoins
            ]);
        }

    }

    public function createPrixAchat()
    {
        $data = Flight::request()->data->getData();

        $id_produit = $data['id_produit'];
        $quantite_achats = $data['quantite'];
        $frais_achats = $data['frais'];
        $prix_unitaire = $this->produitModel->getPrixUnitaireByIdProduit($id_produit);
        $prixTotalAchat = $prix_unitaire * $quantite_achats + ($prix_unitaire * $quantite_achats * $frais_achats / 100);
        $donne = [
            'prix_total' => $prixTotalAchat
        ];
        Flight::json($donne);
    }


    public function validateAchat()
    {
        $data = Flight::request()->data->getData();
        $id_besoin = $this->besoinModel->getIdBesoinByIdProduitAndIdVille($data['id_produit'], $data['id_ville']);
        $quantite = $data['quantite'];

        $this->dispatchModel->createDispatch(0,$id_besoin, $quantite);
        $this->achatModel->createAchat($data);
    }







}
