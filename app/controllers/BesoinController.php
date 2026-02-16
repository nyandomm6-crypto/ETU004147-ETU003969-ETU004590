<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\BesoinModel;

class BesoinController
{
    protected Engine $app;
   

    public function __construct(Engine $app)
    {
        $this->app = $app;
        
    }

    public function createBesoinController(array $data)
    {
        $donnee = [
            'id_ville' => $data['id_ville'],
            'id_produit' => $data['id_produit'],
            'quantite' => $data['quantite'],
            'date_saisie' => $data['date_saisie']
        ];

        $ok = $this->objet->createBesoin($donnee);

        $this->app->redirect('/besoin');
        
       

    }
    public function showBesoin(): array
    {
        return $this->objet->getAllBesoin();
        $this->app->render('listeBesoin.php');
    }
    public function showFormBesoin()
    {
        $this->app->render('formBesoin.php');
    }
}
