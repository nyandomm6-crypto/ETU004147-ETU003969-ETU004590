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
        $stmt = $this->db->prepare("INSERT INTO don (id_produit,quantite,quantite_Initial) VALUES ( ?, ?,?)");
        return $stmt->execute([$data['id_produit'], $data['quantite'], $data['quantite']]);
    }

    public function getAllDon()
    {
        $stmt = $this->db->prepare("SELECT * FROM don");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllDonArgent()
    {
        $stmt = $this->db->prepare("SELECT sum(quantite) as total FROM don
        join produit on don.id_produit = produit.id_produit
        join categorie on produit.id_categorie = categorie.id_categorie
        where categorie.nom_categorie = 'Financier'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

  

    

    
}