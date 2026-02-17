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


 public function getResteDonMateriel($id_produit)
{
    $stmt = $this->db->prepare("
        SELECT SUM(quantite) as total 
        FROM don
        JOIN produit ON don.id_produit = produit.id_produit
        JOIN categorie ON produit.id_categorie = categorie.id_categorie
        WHERE categorie.nom_categorie != 'Financier' 
        AND don.id_produit = ?
    ");
    $stmt->execute([$id_produit]);

    $total = $stmt->fetchColumn();
    
    return (int) $total;
}


  
    public function retirerArgent($montant)
    {
        $stmt = $this->db->prepare("
            SELECT p.id_produit FROM produit p
            JOIN categorie c ON p.id_categorie = c.id_categorie
            WHERE c.nom_categorie = 'Financier'
            LIMIT 1
        ");
        $stmt->execute();
        $id_produit = $stmt->fetchColumn();

        if (!$id_produit) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO don (id_produit, quantite, quantite_Initial) VALUES (?, ?, ?)");
        return $stmt->execute([$id_produit, -$montant, -$montant]);
    }


  

    

    
}