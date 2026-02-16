<?php

namespace app\models;

use Flight;
use PDO;

class CategorieModel
{
    private PDO $db;
    private $id;
    private $nom;



    public function __construct($id = null, $nom = null)
    {
        $this->db = Flight::db();
        $this->id = $id;
        $this->nom = $nom;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }
    public function createCategorie(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categories (nom)
         VALUES (:nom)"
        );

        $ok = $stmt->execute([
            ':nom' => $data['nom']
        ]);

        if ($ok) {
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }
    public function insertImgCategorie($idCategorie, $imgPath): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO photo_categorie (id_categorie, path)
         VALUES (:id_categorie, :path)"
        );

         $stmt->execute([
            ':id_categorie' => $idCategorie,
            ':path' => $imgPath
        ]);
    }

    public function deleteCategorie($id): void
    {
        $stmt = $this->db->prepare(
            "DELETE FROM photo_categorie where id_categorie = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $stmt = $this->db->prepare(
            "DELETE FROM categories where id = :id"
        );

        $stmt->execute([
            ':id' => $id
        ]);
    }
    public function updateCategorie($id, array $data): void
    {
        $stmt = $this->db->prepare(
            "UPDATE categories set nom = :nom where id = :id"
        );

        $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom']
        ]);
    }
    public function getCategorieById($id): ?CategorieModel
    {
        $stmt = $this->db->prepare("SELECT id, nom FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new CategorieModel($row['id'], $row['nom']);
        }

        return null;
    }
    public function getAllCategories(): array
    {
        $stmt = $this->db->query(
            "SELECT 
                c.id   AS id,
                c.nom  AS nom,
                pc.id  AS photo_id,
                pc.path AS path
            FROM categories c
            JOIN photo_categorie pc ON pc.id_categorie = c.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
