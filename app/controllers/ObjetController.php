<?php

namespace app\controllers;
use app\controllers\CategorieController;


use app\models\CategorieModel;
use app\models\DemandeModel;
use Flight;
use flight\Engine;
use app\models\ObjetModels;

class ObjetController
{
    protected Engine $app;
    protected ObjetModels $objet;
    protected CategorieModel $categorieModel;
    protected DemandeModel $demandeModel;



    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->objet = new ObjetModels();
        $this->categorieModel = new CategorieModel();
        $this->demandeModel = new DemandeModel();

    }
    public function showForm()
    {

        if (isset($_SESSION['user'])) {
            $categories = $this->categorieModel->getAllCategories();
            $user = unserialize($_SESSION['user']);
            $this->app->render('formulaireUser', [
                'base_url' => Flight::get('flight.base_url'),
                'user' => $user,
                'categories' => $categories
            ]);
        } else {
            $this->app->redirect('/login');
            return;
        }
    }

    public function create()
    {
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            $data = Flight::request()->data->getData();

            $donne = [
                'nom' => $data['nom'],
                'description' => $data['description'],
                'prix' => $data['prix'],
                'etat' => $data['etat'],
                'id_categorie' => isset($data['categorie']) ? (int) $data['categorie'] : null,
                'id_user' => $user ? $user->getId() : 0
            ];

            $id = $this->objet->createObjet($donne);

                   $image = $_FILES['img_objet'];
                    $upload = $this->uploadPhoto($image);
                    $imgName = $upload['filename'];

                 if ($imgName) {
                       $this->objet->insertImgObjet($id, $imgName);
                 }
                $this->app->redirect('/accueil');
            
        } else {
            $this->app->redirect('/login');
            return;
        }

    }

    public function showDetail($id)
    {
        $user = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
        $imagesObjet = $this->objet->getAllImgObjet((int) $id);
        $objetData = $this->objet->findById((int) $id);

        if ($objetData) {
            $this->app->render('objetDetail', [
                'base_url' => Flight::get('flight.base_url'),
                'user' => $user,
                'objet' => $objetData,
                'images' => $imagesObjet
            ]);
        } else {
            $this->app->redirect('/accueil');
        }
    }

    public function showAccueil()
    {

        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);

            $objects = $this->objet->getByUser($user->getId());
            $this->app->render('accueilUser', [
            'base_url' => Flight::get('flight.base_url'),
            'user' => $user,
            'objects' => $objects
        ]);
        } else {
            $this->app->redirect('/login');
            return;
        }
     
    }

    public function showListOther()
    {
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $userId = unserialize($user)->getId();
            $objects = $this->objet->getAllObjetOther($userId);

            
            $categories = $this->categorieModel->getAllCategories();

            // Objets déjà demandés par l'utilisateur (en cours)
            $objetsEnDemande = $this->demandeModel->getObjetsEnDemandeByUser($userId);

            $this->app->render('listObjetOther', [
                'base_url' => Flight::get('flight.base_url'),
                'user' => $user,
                'objects' => $objects,
                'categories' => $categories,
                'my_objects' => $this->objet->getByUser($userId),
                'objets_en_demande' => $objetsEnDemande
            ]);
        } else {
            $this->app->redirect('/login');
        }
    }

    public function showListMine()
    {
        $user = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
        $userId = $user ? $user->getId() : 0;
        $objects = $this->objet->getAllObjetMine($userId);

        $this->app->render('listObjetMine', [
            'base_url' => Flight::get('flight.base_url'),
            'user' => $user,
            'objects' => $objects
        ]);
    }

    public function search()
{
    $request = Flight::request()->query;

    // Mot cle
    $q = '';
    if (isset($request->q)) {
        $q = trim($request->q);
    }

    // Categorie
    $cat = null;
    if (isset($request->cat) && $request->cat !== '') {
        $cat = (int)$request->cat;
    }

    // Page
    $page = 1;
    if (isset($request->page) && (int)$request->page > 0) {
        $page = (int)$request->page;
    }

    $perPage = 50;
    $offset = ($page - 1) * $perPage;

    // Utilisateur connecte
    $excludeUserId = null;
    if (isset($_SESSION['user'])) {
        $user = unserialize($_SESSION['user']);
        $excludeUserId = $user->getId();
    }

    // Recherche
    $objects = $this->objet->search(
        $q !== '' ? $q : null,
        $cat,
        $excludeUserId,
        $perPage,
        $offset
    );

    // Objets déjà demandés par l'utilisateur (en cours)
    $objetsEnDemande = [];
    $myObjects = [];
    if ($excludeUserId !== null) {
        $objetsEnDemande = $this->demandeModel->getObjetsEnDemandeByUser($excludeUserId);
        $myObjects = $this->objet->getByUser($excludeUserId);
    }

    // resultat
    $this->app->render('listObjetOther', [
        'base_url'   => Flight::get('flight.base_url'),
        'user'       => isset($_SESSION['user']) ? $_SESSION['user'] : null,
        'objects'    => $objects,
        'categories' => $this->categorieModel->getAllCategories(),
        'q'          => $q,
        'cat'        => $cat,
        'page'       => $page,
        'my_objects' => $myObjects,
        'objets_en_demande' => $objetsEnDemande
    ]);
}


    public function showEditForm($id)
    {
        $user = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
        $objetData = $this->objet->findById((int) $id);

        $allowed = false;
        if ($objetData) {
            if ($user && $objetData['id_user'] == $user->getId()) {
                $allowed = true;
            } elseif (!$user && (int) $objetData['id_user'] === 0) {
                $allowed = true;
            }
        }

        if (!$allowed) {
            $this->app->redirect('/objet/mine');
            return;
        }

        $db = Flight::db();
        $stmt = $db->query('SELECT id, nom FROM categories ORDER BY nom');
        $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->app->render('editObjet', [
            'base_url' => Flight::get('flight.base_url'),
            'user' => $user,
            'objet' => $objetData,
            'categories' => $categories
        ]);
    }

    public function update($id)
    {


        $user = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
        $objetData = $this->objet->findById((int) $id);
        $image = $_FILES['img_objet'];
        $upload = $this->uploadPhoto($image);
        $imgName = $upload['filename'];

                 if ($imgName) {
                       $this->objet->insertImgObjet($id, $imgName);
                 }


        $allowed = false;
        if ($objetData) {
            if ($user && $objetData['id_user'] == $user->getId()) {
                $allowed = true;
            } elseif (!$user && (int) $objetData['id_user'] === 0) {
                $allowed = true;
            }
        }

        if (!$allowed) {
            $this->app->redirect('/objet/mine');
            return;
        }

        $data = Flight::request()->data->getData();

        $payload = [
            'nom' => $data['nom'] ?? '',
            'description' => $data['description'] ?? null,
            'prix' => $data['prix'] ?? 0,
            'etat' => $data['etat'] ?? 0,
            'id_categorie' => isset($data['categorie']) ? (int) $data['categorie'] : null
        ];

        $this->objet->updateObjet((int) $id, $payload);
        $this->app->redirect('/objet/mine');
    }

    public function delete($id)
    {

        $user = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
        $objetData = $this->objet->findById((int) $id);

        if ($objetData) {
            if ($user && $objetData['id_user'] == $user->getId()) {
                $this->objet->deleteObjet((int) $id);
            } elseif (!$user && (int) $objetData['id_user'] === 0) {
                $this->objet->deleteObjet((int) $id);
            }
        }

        $this->app->redirect('/objet/mine');
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
    
}
