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
}
