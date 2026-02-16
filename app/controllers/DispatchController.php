<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\DispatchModel;
use app\models\BesoinModel;

class DispatchController
{
    protected Engine $app;

    protected DispatchModel $dispatchModel;
    protected BesoinModel $besoinModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->dispatchModel = new DispatchModel();
        $this->besoinModel = new BesoinModel();
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
            'besoin' => $this->besoinModel->getAllBesoin()
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
}
