<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\ObjetModels;

class BesoinController
{
    protected Engine $app;
    protected ObjetModels $objet;

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

        
        if (!$ok) { 
            $this->app->redirect('/accueil');
        }

    }
    public function showBesoin(): array
    {
        return $this->objet->getAllBesoin();
    }
    public function showFormBesoin()
    {
        $this->app->render('formBesoin.php');
    }
}
