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
        // cities that have a besoin for this product
        $stmt = $this->db->prepare("SELECT DISTINCT v.id_ville, v.nom_ville FROM ville v JOIN besoin b ON v.id_ville = b.id_ville WHERE b.id_produit = ?");
        $stmt->execute([$id_produit]);
        $ville = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // total besoin per city for this product
        $stmsumBesoin = $this->db->prepare("SELECT b.id_ville, SUM(b.quantite) AS total_besoin FROM besoin b WHERE b.id_produit = ? GROUP BY b.id_ville");
        $stmsumBesoin->execute([$id_produit]);
        $sumBesoin = $stmsumBesoin->fetchAll(PDO::FETCH_ASSOC);

        // total attribue per city for this product (from dispatch -> besoin -> ville)
        $stmsumAttribue = $this->db->prepare(
            "SELECT b.id_ville, COALESCE(SUM(d.quantite_attribuee),0) AS total_attribue
             FROM dispatch d
             JOIN besoin b ON d.id_besoin = b.id_besoin
             WHERE b.id_produit = ?
             GROUP BY b.id_ville"
        );
        $stmsumAttribue->execute([$id_produit]);
        $sumAttribue = $stmsumAttribue->fetchAll(PDO::FETCH_ASSOC);

        $sumBesoinRestant = [];
        $attribueByVille = [];
        foreach ($sumAttribue as $a) {
            $attribueByVille[(int)$a['id_ville']] = (int)$a['total_attribue'];
        }
        foreach ($sumBesoin as $b) {
            $vid = (int)$b['id_ville'];
            $totalBesoin = (int)$b['total_besoin'];
            $totalAttribue = $attribueByVille[$vid] ?? 0;
            $sumBesoinRestant[] = [
                'id_ville' => $vid,
                'total_restant' => max(0, $totalBesoin - $totalAttribue)
            ];
        }
        return ['ville' => $ville, 'sumBesoin' => $sumBesoin, 'sumBesoinRestant' => $sumBesoinRestant];
    }

}