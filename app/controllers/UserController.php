<?php

namespace app\controllers;
use app\models\User;
use Flight;
use flight\Engine;


class UserController
{

    protected Engine $app;
    protected User $userModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->userModel = new User($app);
    }
    public function afficherLogin()
    {
        $this->app->render('auth/login', ['base_url' => Flight::get('flight.base_url'), 'activePage' => 'login']);
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            if ($user->getType() == "admin") {
                $this->app->redirect('/accueilAdmin');
            } else if ($user->getType() == "user") {
                $this->app->redirect('/accueil');
            } else {
                $this->app->redirect('/logout');
            }
        }
    }

    public function afficherSignUp()
    {
        $this->app->render('auth/signUp', ['base_url' => Flight::get('flight.base_url'), 'activePage' => 'signUp']);
    }


    public function deconnecterUser()
    {
        unset($_SESSION['user']);
        $this->app->redirect('/login');
    }

    public function validateName()
    {
        $data = Flight::request()->data->getData();
        $this->app->json([
            'message' => $this->userModel->validateName($data)
        ]);
    }

    public function validateEmail()
    {
        $data = Flight::request()->data->getData();
        $this->app->json([
            'message' => $this->userModel->validateEmail($data)
        ]);
    }

    public function validatePassword()
    {
        $data = Flight::request()->data->getData();
        $this->app->json([
            'message' => $this->userModel->validatePassword($data)
        ]);
    }

    public function signUp()
    {
        $data = Flight::request()->data->getData();

        $result = $this->userModel->signUp($data);

        $this->app->json($result);
    }


    public function login()
    {
        $data = Flight::request()->data->getData();
        $result = $this->userModel->login($data);
        if ($result['success']) {
            $_SESSION['user'] = $result['user'];
        }
        $this->app->json($result);
    }

    public function getAllUserInscrit()
    {
        $result = $this->userModel->getAllUserInscrit();
        $this->app->render('listUser', ['base_url' => Flight::get('flight.base_url'),'users' => $result]);


    }
}
