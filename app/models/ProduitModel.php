<?php

namespace app\models;

use Flight;
use PDO;

class ProduitModel
{
    private PDO $db;

    public function __construct($id = null, $nom = null, $email = null, $type = null)
    {
        $this->db = Flight::db();
    }

    public function getAllProduits()
    {
        $stmt = $this->db->query("SELECT * FROM produit");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM produit WHERE id_produit = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getPrixUnitaireByIdProduit($id_produit)
    {
        $stmt = $this->db->prepare("SELECT prix_unitaire FROM produit WHERE id_produit = ?");
        $stmt->execute([$id_produit]);
        return $stmt->fetchColumn();
    }
}