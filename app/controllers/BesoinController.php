<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\BesoinModel;

class BesoinController
{
    protected Engine $app;
    protected BesoinModel $besoinModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->besoinModel = new BesoinModel();
    }

    public function createBesoinController()
    {
        $request = Flight::request();
        $base_url = Flight::get('flight.base_url');
        
         $donnee = [
            'id_ville' => $request->data->id_ville,
            'id_produit' => $request->data->id_produit,
            'quantite' => $request->data->quantite,
            'date_saisie' => $request->data->date_saisie 
        ];

        $this->besoinModel->createBesoin($donnee);

        $this->app->redirect($base_url . '/besoin');
    }

    public function showBesoin()
    {
        $base_url = Flight::get('flight.base_url');
        $besoins = $this->besoinModel->getAllBesoin();
        $this->app->render('listeBesoin.php', [
            'base_url' => $base_url,
            'besoins' => $besoins
        ]);
    }

    public function showFormBesoin()
    {
        $base_url = Flight::get('flight.base_url');
        $this->app->render('formBesoin.php', [
            'base_url' => $base_url
        ]);
    }
}
