<?php
namespace app\models;

use Flight;
use PDO;

class AchatModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function createAchat(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO achat (id_produit, quantite_achats, frais_achats)
             VALUES (:id_produit, :quantite, :frais)"
        );

        return $stmt->execute([
            ':id_produit' => $data['id_produit'],
            ':quantite' => $data['quantite'],
            ':frais' => $data['frais']
        ]);
    }




}