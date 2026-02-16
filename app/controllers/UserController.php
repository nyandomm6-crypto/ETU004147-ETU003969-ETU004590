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
}
