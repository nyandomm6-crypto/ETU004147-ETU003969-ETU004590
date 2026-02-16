<?php

namespace app\controllers;
use app\models\DonModel;
use app\models\User;
use app\models\VilleModel;
use app\models\BesoinModel;
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
        $villes = $this->villeModel->getAllVille();
        $dons = $this->donModel->getAllDon();
        $besoins = $this->besoinModel->getAllBesoin();

        $this->app->render('dashboard.php', [
            'villes' => $villes,
            'dons' => $dons,
            'besoins' => $besoins
        ]);
    }


}
