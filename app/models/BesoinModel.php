<?php

namespace app\models;

use Flight;
use PDO;

class BesoinModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function createBesoin(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO besoin (id_ville, id_produit, quantite, date_saisie)
             VALUES (:id_ville, :id_produit, :quantite, :date_saisie)"
        );

        return $stmt->execute([
            ':id_ville' => $data['id_ville'],
            ':id_produit' => $data['id_produit'],
            ':quantite' => $data['quantite'],
            ':date_saisie' => $data['date_saisie']
        ]);
    }
    public function getAllBesoin(): array
    {
        $stmt = $this->db->query("SELECT * FROM besoin");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne les besoins pour une ville donnée avec les informations produit
     */
    public function getBesoinsByVille(int $id_ville): array
    {
        $sql = "SELECT b.*, p.nom_produit, p.prix_unitaire, v.nom_ville
                FROM besoin b
                JOIN produit p ON b.id_produit = p.id_produit
                JOIN ville v ON b.id_ville = v.id_ville
                WHERE b.id_ville = :id_ville
                ORDER BY b.date_saisie ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_ville' => $id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne toutes les villes avec leurs besoins (même vides)
     * Format: array of ['id_ville','nom_ville','besoins' => []]
     */
    public function getVillesWithBesoins(): array
    {
        $sql = "SELECT v.id_ville, v.nom_ville,
                       b.id_besoin, b.id_produit, b.quantite, b.date_saisie,
                       p.nom_produit, p.prix_unitaire
                FROM ville v
                LEFT JOIN besoin b ON v.id_ville = b.id_ville
                LEFT JOIN produit p ON b.id_produit = p.id_produit
                ORDER BY v.nom_ville, b.date_saisie ASC";

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $r) {
            $vid = $r['id_ville'];
            if (!isset($result[$vid])) {
                $result[$vid] = [
                    'id_ville' => $vid,
                    'nom_ville' => $r['nom_ville'],
                    'besoins' => []
                ];
            }

            if (!empty($r['id_besoin'])) {
                $result[$vid]['besoins'][] = [
                    'id_besoin' => $r['id_besoin'],
                    'id_produit' => $r['id_produit'],
                    'quantite' => $r['quantite'],
                    'date_saisie' => $r['date_saisie'],
                    'nom_produit' => $r['nom_produit'],
                    'prix_unitaire' => $r['prix_unitaire']
                ];
            }
        }

        // reindex as numeric array
        return array_values($result);
    }

    
}
