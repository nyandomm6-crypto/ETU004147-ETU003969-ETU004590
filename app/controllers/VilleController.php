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
        $villes = $this->villeModel->getAllVille();
        $dons = $this->donModel->getAllDon();

        // load products to enrich dons and besoins
        $produitModel = new ProduitModel();
        $produits = $produitModel->getAllProduits();
        $prodMap = [];
        foreach ($produits as $p) {
            $prodMap[$p['id_produit']] = $p;
        }

        // enrich dons with product name
        foreach ($dons as &$don) {
            $pid = $don['id_produit'] ?? null;
            if ($pid !== null && isset($prodMap[$pid])) {
                $don['nom_produit'] = $prodMap[$pid]['nom_produit'];
                $don['prix_unitaire'] = $prodMap[$pid]['prix_unitaire'];
            }
        }
        unset($don);

        // attach besoins per ville using BesoinModel::getBesoinsByVille
        foreach ($villes as &$ville) {
            $idv = $ville['id_ville'] ?? null;
            if ($idv !== null) {
                $ville['besoins'] = $this->besoinModel->getBesoinsByVille((int)$idv);

                // attach dons for this ville
                $ville['dons'] = array_values(array_filter($dons, function($d) use ($idv) {
                    return isset($d['id_ville']) && (int)$d['id_ville'] === (int)$idv;
                }));
            } else {
                $ville['besoins'] = [];
                $ville['dons'] = [];
            }
        }
        unset($ville);

        $this->app->render('dashboard.php', [
            'base_url' => Flight::get('base_url'),
            'villes' => $villes,
            'dons' => $dons
        ]);
    }


}
