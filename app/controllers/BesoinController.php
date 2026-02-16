<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\BesoinModel;
use app\models\ProduitModel;
use app\models\VilleModel;



class BesoinController
{
    protected Engine $app;
    protected VilleModel $villeModel;
    protected BesoinModel $besoinModel;
    protected ProduitModel $produitModel;

   

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->villeModel = new VilleModel();
        $this->besoinModel = new BesoinModel();
        $this->produitModel = new ProduitModel();

        
    }

    public function createBesoinController(array $data)
    {
        $donnee = [
            'id_ville' => $data['id_ville'],
            'id_produit' => $data['id_produit'],
            'quantite' => $data['quantite'],
            'date_saisie' => $data['date_saisie']
        ];

        $ok = $this->besoinModel->createBesoin($donnee);

         $this->app->render('listeBesoin.php', [
            'base_url' => Flight::get('flight.base_url'),
            'villes' => $this->villeModel->getAllVille(),
            'produits' => $this->produitModel->getAllProduits()
        ]);
        
       

    }
    public function showListBesoin(): array
    {
        return $this->besoinModel->getAllBesoin();
         $this->app->render('listeBesoin.php', [
            'base_url' => Flight::get('flight.base_url'),
            'besoins' => $this->besoinModel->getAllBesoin(),
            'villes' => $this->villeModel->getAllVille(),
            'produits' => $this->produitModel->getAllProduits()
        ]);
    }   
    public function showFormBesoin()
    {
        $this->app->render('formBesoin.php', [
            'base_url' => Flight::get('flight.base_url'),
            'produits' => $this->produitModel->getAllProduits(),
            'villes' => $this->villeModel->getAllVille(),
            'current_date' => date('Y-m-d')
        ]); 
    }
}
