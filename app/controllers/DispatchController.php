<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\DispatchModel;

class DispatchController
{
    protected Engine $app;

    protected DispatchModel $dispatchModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->dispatchModel = new DispatchModel();
    }


    public function showAllDispatch()
    {
        $data = Flight::request()->data->getData();
        $id_besoin = $data['id_besoin'];
        $dispatch = $this->dispatchModel->getDispatchId($id_besoin);
        $this->app->render('showDispatch.php', [
            'base_url' => Flight::get('base_url'),
            'dispatch' => $dispatch
        ]);
    }

    public function showDispatchDetail($id_dispatch)
    {
        $dispatch = $this->dispatchModel->getDispatchDetail($id_dispatch);
        $this->app->render('showDispatchDetail.php', [
            'base_url' => Flight::get('base_url'),
            'dispatch' => $dispatch
        ]);
    }

    public function formDispatch(){
        
    }


}
