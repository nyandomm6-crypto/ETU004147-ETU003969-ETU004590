<?php

namespace app\models;


use Flight;
use PDO;
use app\models\ProduitModel;


class VilleModel
{
    private PDO $db;
    private ProduitModel $produitModel;

    public function __construct()
    {
        $this->db = Flight::db();
        $this->produitModel = new ProduitModel();
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
            $attribueByVille[(int) $a['id_ville']] = (int) $a['total_attribue'];
        }
        foreach ($sumBesoin as $b) {
            $vid = (int) $b['id_ville'];
            $totalBesoin = (int) $b['total_besoin'];
            $totalAttribue = $attribueByVille[$vid] ?? 0;
            $sumBesoinRestant[] = [
                'id_ville' => $vid,
                'total_restant' => max(0, $totalBesoin - $totalAttribue)
            ];
        }
        return ['ville' => $ville, 'sumBesoin' => $sumBesoin, 'sumBesoinRestant' => $sumBesoinRestant];
    }


    public function getRestant_besoin_parVille()
    {
        // Directly compute per-city per-product remaining: SUM(b.quantite) - SUM(d.quantite_attribuee)
        $sql = "SELECT b.id_ville, v.nom_ville, b.id_produit, p.nom_produit,
                       SUM(b.quantite) AS total_besoin,
                       COALESCE(SUM(d.quantite_attribuee),0) AS total_attribue
                FROM besoin b
                LEFT JOIN dispatch d ON b.id_besoin = d.id_besoin
                LEFT JOIN ville v ON b.id_ville = v.id_ville
                LEFT JOIN produit p ON b.id_produit = p.id_produit
                GROUP BY b.id_ville, v.nom_ville, b.id_produit, p.nom_produit";

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $r) {
            $totalBesoin = (int)($r['total_besoin'] ?? 0);
            $totalAttribue = (int)($r['total_attribue'] ?? 0);
            $result[] = [
                'id_ville' => (int)$r['id_ville'],
                'nom_ville' => $r['nom_ville'] ?? null,
                'id_produit' => (int)$r['id_produit'],
                'nom_produit' => $r['nom_produit'] ?? null,
                'total_besoin' => $totalBesoin,
                'total_attribue' => $totalAttribue,
                'total_restant' => max(0, $totalBesoin - $totalAttribue)
            ];
        }

        return $result;
    }




}