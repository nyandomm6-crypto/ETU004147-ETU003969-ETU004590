<?php

namespace app\controllers;

use app\models\ProduitModel;
use app\models\VilleModel;
use Flight;
use flight\Engine;
use app\models\DispatchModel;
use app\models\BesoinModel;

class DispatchController
{
    protected Engine $app;

    protected DispatchModel $dispatchModel;
    protected BesoinModel $besoinModel;
    protected ProduitModel $produitModel;
    protected VilleModel $villeModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->dispatchModel = new DispatchModel();
        $this->besoinModel = new BesoinModel();
        $this->produitModel = new ProduitModel();
        $this->villeModel = new VilleModel();
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
            'besoin' => $this->besoinModel->getAllBesoin(),
            'produit' => $this->produitModel->getAllProduits()

        ]);
    }

    public function createDispatch()
    {
        $data = Flight::request()->data->getData();
        $mode = $data['mode_distribution'] ?? 'manuel';
        $id_produit = $data['id_produit'] ?? null;
        $quantite = (int)($data['quantite'] ?? 0);

        $result = ['success' => false, 'message' => 'Mode non reconnu'];

        if ($mode === 'manuel') {
            // Mode manuel : dispatch sur un besoin spécifique
            $id_besoin = $data['id_besoin'] ?? null;
            if ($id_besoin && $quantite > 0) {
                $result = $this->dispatchModel->dispatchDon($id_besoin, $quantite);
            }
        } elseif ($mode === 'par_date' && $id_produit && $quantite > 0) {
            // Mode automatique par date de saisie
            $result = $this->dispatchModel->dispatchAutomatiqueParDate($id_produit, $quantite);
        } elseif ($mode === 'par_petit' && $id_produit && $quantite > 0) {
            // Mode automatique par plus petite quantité
            $result = $this->dispatchModel->dispatchAutomatiqueParPlusPetit($id_produit, $quantite);
        } elseif ($mode === 'par_proportion' && $id_produit && $quantite > 0) {
            // Mode automatique par proportionnalité
            $result = $this->dispatchModel->dispatchAutomatiqueParProportionnalite($id_produit, $quantite);
        }

        // Marquer les besoins individuels épuisés (quantite = 0)
        $this->villeModel->updateBesoinEpuise();

        // Stocker le résultat en session pour affichage
        $_SESSION['dispatch_result'] = $result;

        $this->app->redirect('/');
    }

    //json - info besoins par produit
    public function getInfoByProduit()
    {
        $data = Flight::request()->data->getData();
        $id_produit = (int)($data['id_produit'] ?? 0);
        
        if ($id_produit <= 0) {
            Flight::json(['error' => 'Produit invalide']);
            return;
        }

        $result = $this->villeModel->getVillebyIdProduit($id_produit);
        $donRestant = $this->dispatchModel->getDonRestantByProduit($id_produit);
        
        $result['donRestant'] = $donRestant;
        Flight::json($result);
    }

    // Afficher le tableau récapitulatif
    public function showTableauRecapitulatif()
    {
        $recap = $this->dispatchModel->getTableauRecapitulatif();
        $this->app->render('tableauRecapitulatif.php', [
            'base_url' => Flight::get('flight.base_url'),
            'recap' => $recap
        ]);
    }
}
