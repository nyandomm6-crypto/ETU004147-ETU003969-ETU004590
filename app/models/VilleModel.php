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


    public function getVillebyIdProduit($id_produit)
    {
        $stmt = $this->db->prepare("SELECT v.* FROM ville v JOIN besoin b ON v.id_ville = b.id_ville WHERE b.id_produit = ? GROUP BY v.id_ville");
        $stmt->execute([$id_produit]);
        $ville = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmsumBesoin = $this->db->prepare("SELECT v.*, SUM(b.quantite) AS total_besoin FROM ville v JOIN besoin b ON v.id_ville = b.id_ville GROUP BY v.id_ville");
        $stmsumBesoin->execute();
        $sumBesoin = $stmsumBesoin->fetchAll(PDO::FETCH_ASSOC);
        $stmsumBesoinRestant = $this->db->prepare("SELECT d.id_ville, SUM(d.quantite_initial - d.quantite) AS total_restant FROM don d WHERE d.id_produit = ? GROUP BY d.id_ville");
        $stmsumBesoinRestant->execute([$id_produit]);
        $sumBesoinRestant = $stmsumBesoinRestant->fetchAll(PDO::FETCH_ASSOC);
        return ['ville' => $ville, 'sumBesoin' => $sumBesoin, 'sumBesoinRestant' => $sumBesoinRestant];
    }

}