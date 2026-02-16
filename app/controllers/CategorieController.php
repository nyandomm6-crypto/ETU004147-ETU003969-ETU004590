<?php

namespace app\controllers;


use Flight;
use flight\Engine;
use app\models\CategorieModel;
use app\models\ObjetModels;
use app\models\DemandeModel;
use app\models\HistoModel;
use app\models\User;




class CategorieController
{
    protected CategorieModel $categorieModel;
    protected ObjetModels $objet;
    protected DemandeModel $demande;
    protected HistoModel $histo;
    protected User $users;


    protected Engine $app;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->categorieModel = new CategorieModel();
        $this->objet = new ObjetModels();
        $this->demande = new DemandeModel();
        $this->histo = new HistoModel();
        $this->users = new User();
    }

    public function afficherFormCategorie()
    {
        $this->app->render('formCategorie', ['base_url' => Flight::get('flight.base_url')]);
    }


    public function createCategorie()
    {
        $data = Flight::request()->data->getData();

        $categorie = [];
        $categorie['nom'] = $data['nom'];
        $image = $_FILES['img_categorie'];

        $upload = $this->uploadPhoto($image);
        $imgName = $upload['filename'];

        $id = $this->categorieModel->createCategorie($categorie);
        if ($imgName) {
            $this->categorieModel->insertImgCategorie($id, $imgName);
        }
        $this->app->redirect('/listCategorie');

    }

    public function deleteCategorie()
    {
        $data = Flight::request()->data->getData();
        $id = $data['id_categorie'];

        $this->categorieModel->deleteCategorie($id);
        $this->getListCategorie();
    }
    public function updateCategorie()
    {
        $data = Flight::request()->data->getData();

        $id = $data['id_categorie'];
        $categorie = [];
        $categorie['nom'] = $data['nom'];

        $this->categorieModel->updateCategorie($id, $categorie);
        $this->getListCategorie();
    }

    public function getListCategorie()
    {
        $categories = $this->categorieModel->getAllCategories();
        $this->app->render('listCategorie', ['categories' => $categories, 'base_url' => Flight::get('flight.base_url')]);
    }


    public function uploadPhoto($file)
    {
        // Vérifier qu'il y a un fichier
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Aucun fichier sélectionné ou erreur lors de l\'upload'];
        }

        // Vérifier le type de fichier (ex: jpg, png, gif)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExt, $allowedExtensions)) {
            return ['success' => false, 'message' => 'Extension de fichier non autorisée'];
        }

        // Créer un nom unique pour éviter les conflits
        $uniqueName = uniqid('img_', true) . '.' . $fileExt;

        // Dossier de destination (assure-toi qu'il existe et est accessible en écriture)
        $uploadDir = __DIR__ . '/../../public/assets/images/'; // adapte selon ton projet
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Déplacer le fichier uploadé vers le dossier
        $destination = $uploadDir . $uniqueName;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Retourner le nom du fichier enregistré
            return ['success' => true, 'filename' => $uniqueName];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement du fichier'];
        }
    }


    public function showAccueilAdmin()
    {

        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            if ($user->getType() === 'admin') {

                $this->app->render('accueilAdmin', [
                    'base_url' => Flight::get('flight.base_url'),
                    'user' => $user
                ]);
            } else {
                $objects = $this->objet->getByUser($user->getId());
                $this->app->render('accueilUser', [
                    'base_url' => Flight::get('flight.base_url'),
                    'user' => $user,
                    'objects' => $objects
                ]);
            }
        } else {
            $user = null;
        }

    }

    public function showStatistique()
    {
        $nbrUser = $this->users->getAllUserInscrit();
        $nbrHisto = $this->histo->getAllHisto();
        $this->app->render('statistique', ['base_url' => Flight::get('flight.base_url'), 'nbrUser' => $nbrUser, 'nbrHisto' => $nbrHisto]);
    }


}