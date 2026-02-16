<?php

namespace app\controllers;

use Flight;
use flight\Engine;
use app\models\DemandeModel;
use app\models\User;


class DemandeController
{
    protected DemandeModel $demandeModel;
    protected User $userModel;

    protected Engine $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->demandeModel = new DemandeModel();
        $this->userModel = new User();

    }

    public function createDemande()
    {
        $data = Flight::request()->data->getData();
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $demande = [];
            $demande['id_sender'] = $user->getId();
            $demande['id_myobjet'] = $data['id_myobjet'];
            $demande['id_objetEchange'] = $data['id_objetEchange'];

            $id = $this->demandeModel->createDemande($demande);

            // Retourner JSON pour le JS
            $this->app->json([
                'success' => $id > 0,
                'id' => $id
            ]);
            return;
        }

        $this->app->json([
            'success' => false,
            'error' => 'Non connecté'
        ]);
    }
    public function getListDemande()
    {
        $data = Flight::request()->data->getData();
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $id = 6;
            $demandes = $this->demandeModel->getListDemandeByUserId($id);
            $this->app->render('listDemande', ['demandes' => $demandes, 'base_url' => Flight::get('flight.base_url')]);
        }
    }

    public function accepterDemande()
    {
        $data = Flight::request()->data->getData();
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $id = $data['id'];
            $this->demandeModel->updateEtatDemande($id, 'acceptee');
            $this->demandeModel->refuserAllDemandeEncourt(int $idObjet);

            $this->app->json(['success' => true]);
        } else {
            $this->app->json(['success' => false, 'error' => 'Non connecté']);
        }
    }

    public function refuserDemande()
    {
        $data = Flight::request()->data->getData();
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $id = $data['id'];
            $this->demandeModel->updateEtatDemande($id, 'refusee');
            $this->app->json(['success' => true]);
        } else {
            $this->app->json(['success' => false, 'error' => 'Non connecté']);
        }
    }



}