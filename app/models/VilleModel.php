<?php

namespace app\models;

use Flight;
use PDO;

class VilleModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }


    public function createVille($data)
    {
        $stmt = $this->db->prepare("INSERT INTO ville (nom) VALUES (?)");
        return $stmt->execute([$data['nom']]);
    }

    public function getAllVille()
    {
        $stmt = $this->db->prepare("SELECT * FROM ville");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}