<?php

namespace app\controllers;
use app\models\DonModel;
use app\models\User;
use app\models\VilleModel;
use app\models\BesoinModel;
use app\models\ProduitModel;
use app\models\DispatchModel;
use Flight;
use flight\Engine;


class VilleController
{

    protected Engine $app;
    protected VilleModel $villeModel;
    protected DonModel $donModel;
    protected BesoinModel $besoinModel;
    protected DispatchModel $dispatchModel;
    protected ProduitModel $produitModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->villeModel = new VilleModel();
        $this->donModel = new DonModel();
        $this->besoinModel = new BesoinModel();
        $this->dispatchModel = new DispatchModel();
        $this->produitModel = new ProduitModel();
    }

    public function showDashboard()
    {
        $villes = $this->besoinModel->getVillesWithBesoins();
        $dons = $this->donModel->getAllDon();
        $dispatchSummary = $this->dispatchModel->getDispatchSummaryByVille();

        $this->app->render('dashboard.php', [
            'base_url' => Flight::get('flight.base_url'),
            'villes' => $villes,
            'dons' => $dons,
            'dispatch_summary' => $dispatchSummary,
            'produit' => $this->produitModel->getAllProduits()
        ]);
    }


    public function getListBesoinRestant()
    {
        $besoins = $this->villeModel->getResteBesoinParProduitParVille();
        $this->app->render('listBesoinRestant.php', [
            'base_url' => Flight::get('flight.base_url'),
            'besoins' => $besoins
        ]);
    }

}
