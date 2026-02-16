<?php

namespace app\controllers;
use app\models\DonModel;
use app\models\User;
use app\models\VilleModel;
use app\models\BesoinModel;
use app\models\ProduitModel;
use Flight;
use flight\Engine;


class VilleController
{

    protected Engine $app;
    protected VilleModel $villeModel;
    protected DonModel $donModel;
    protected BesoinModel $besoinModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->villeModel = new VilleModel();
        $this->donModel = new DonModel();
        $this->besoinModel = new BesoinModel();
    }

    public function showDashboard()
    {
        $villes = $this->besoinModel->getVillesWithBesoins();
        $dons = $this->donModel->getAllDon();


        $this->app->render('dashboard.php', [
            'base_url' => Flight::get('base_url'),
            'villes' => $villes,
            'dons' => $dons
        ]);
    }


}
