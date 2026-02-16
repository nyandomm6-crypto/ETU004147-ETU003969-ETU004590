<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\HistoModel;
use app\models\User;


class HistoController
{
    protected HistoModel $histoModel;

    protected Engine $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->histoModel = new HistoModel();
    }


    public function getListHisto()
    {
        $histo = $this->histoModel->getAllHisto();
        $this->app->render('listHisto', ['histo' => $histo, 'base_url' => Flight::get('flight.base_url')]);
    }




}