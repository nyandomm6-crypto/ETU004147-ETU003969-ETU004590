<?php

namespace app\models;

use Flight;
use PDO;

class User
{
    private PDO $db;

    public function __construct($id = null, $nom = null, $email = null, $type = null)
    {
        $this->db = Flight::db();
    }
}