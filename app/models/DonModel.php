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
        $stmt = $this->db->prepare("INSERT INTO don (id_produit, id_ville, quantite,quantite_Initial) VALUES (?, ?, ?)");
        return $stmt->execute([$data['id_produit'], $data['id_ville'], $data['quantite'], $data['quantite']]);
    }

    public function getAllDon()
    {
        $stmt = $this->db->prepare("SELECT * FROM don");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}