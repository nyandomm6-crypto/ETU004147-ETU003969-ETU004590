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
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->type = $type;
    }
}