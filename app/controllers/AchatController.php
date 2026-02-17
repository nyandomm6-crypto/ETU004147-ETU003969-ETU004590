<?php

namespace app\controllers;
use app\models\AchatModel;
use app\models\DispatchModel;
use app\models\DonModel;
use app\models\BesoinModel;
use app\models\ProduitModel;
use app\models\VilleModel;
use Flight;
use flight\Engine;


class AchatController
{

    protected Engine $app;
    protected DonModel $donModel;
    protected ProduitModel $produitModel;
    protected AchatModel $achatModel;

    protected VilleModel $villeModel;

    protected DispatchModel $dispatchModel;

    protected BesoinModel $besoinModel;


    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->donModel = new DonModel();
        $this->produitModel = new ProduitModel();
        $this->achatModel = new AchatModel();
        $this->villeModel = new VilleModel();
        $this->dispatchModel = new DispatchModel();
        $this->besoinModel = new BesoinModel();
    }

    public function showFormAchat()
    {
        $data = Flight::request()->data->getData();

        //verification si dont existe

        $id_produit = $data['id_produit'];
        if ($this->donModel->getResteDonMateriel($id_produit) == 0) {
            $this->app->render('formAchat', [
                'base_url' => Flight::get('flight.base_url'),
                'id_produit' => $data['id_produit'],
                'id_ville' => $data['id_ville']
            ]);
        } else {
            $besoins = $this->villeModel->getResteBesoinParProduitParVille();
            $this->app->render('listBesoinRestant.php', [
                'error' => 'don mbola misy',
                'base_url' => Flight::get('flight.base_url'),
                'besoins' => $besoins
            ]);
        }

    }

    public function createPrixAchat()
    {
        $data = Flight::request()->data->getData();

        $id_produit = $data['id_produit'];
        $quantite_achats = $data['quantite'];
        $frais_achats = $data['frais'];
        $prix_unitaire = $this->produitModel->getPrixUnitaireByIdProduit($id_produit);
        $prixTotalAchat = $prix_unitaire * $quantite_achats + ($prix_unitaire * $quantite_achats * $frais_achats / 100);
        $donne = [
            'prix_total' => $prixTotalAchat
        ];
        Flight::json($donne);
    }


    public function validateAchat()
    {
        $data = Flight::request()->data->getData();
        $id_besoin = $this->besoinModel->getIdBesoinByIdProduitAndIdVille($data['id_produit'], $data['id_ville']);
        $quantite = $data['quantite'];

        $id_produit = $data['id_produit'];
        $frais = $data['frais'] ?? 0;
        $prix_unitaire = $this->produitModel->getPrixUnitaireByIdProduit($id_produit);
        $prixTotalAchat = $prix_unitaire * $quantite + ($prix_unitaire * $quantite * $frais / 100);

        $donArgentResult = $this->donModel->getAllDonArgent();
        $budgetDisponible = (float)($donArgentResult[0]['total'] ?? 0);

        if ($prixTotalAchat > $budgetDisponible) {
            // Budget insuffisant — ne pas valider
            $this->app->render('formAchat', [
                'base_url' => Flight::get('flight.base_url'),
                'id_produit' => $data['id_produit'],
                'id_ville' => $data['id_ville'],
                'error' => 'Budget insuffisant. Prix estimatif (' . number_format($prixTotalAchat, 2) . ' Ar) dépasse le don argent disponible (' . number_format($budgetDisponible, 2) . ' Ar).'
            ]);
            return;
        }

        $this->dispatchModel->createDispatch(0, $id_besoin, $quantite);
        $this->achatModel->createAchat($data);

        // Retirer le montant du don argent
        $this->donModel->retirerArgent($prixTotalAchat);

        // Marquer les besoins individuels épuisés (quantite = 0)
        $this->villeModel->updateBesoinEpuise();

        $this->app->redirect('/listBesoinRestant');
    }


    public function getDonArgent()
    {
        $result = $this->donModel->getAllDonArgent();
        $total = (float)($result[0]['total'] ?? 0);
        Flight::json(['budget' => $total]);
    }







}
