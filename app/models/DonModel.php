<?php

namespace app\models;

use Flight;
use PDO;

class DonModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }


    public function createDon($data)
    {
        $stmt = $this->db->prepare("INSERT INTO don (id_produit, id_ville, quantite) VALUES (?, ?, ?)");
        return $stmt->execute([$data['id_produit'], $data['id_ville'], $data['quantite']]);
    }
}