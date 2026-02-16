<?php

namespace app\models;

use Flight;
use PDO;

class ObjetModels
{
    private PDO $db;
    private $id;

    public function __construct($id = null)
    {
        $this->db = Flight::db();
        $this->id = $id;
    }

    public function __sleep()
    {
        return ['id'];
    }

    public function __wakeup()
    {
        $this->db = Flight::db();
    }

    public function createObjet(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO objet (nom, description, prix, etat, id_categorie, id_user)
             VALUES (:nom, :description, :prix, :etat, :id_categorie, :id_user)"
        );

        $ok = $stmt->execute([
            ':nom' => $data['nom'],
            ':description' => $data['description'] ?? null,
            ':prix' => $data['prix'] ,
            ':etat' => $data['etat'] ,
            ':id_categorie' => $data['id_categorie'] ?? null,
            ':id_user' => $data['id_user']
        ]);

        if ($ok) {
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }

 
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT o.*,c.nom as categorie_nom, u.nom as user_nom FROM objet o
        join categories c on o.id_categorie = c.id
        join users u on o.id_user = u.id
        WHERE o.id = :id");

        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    public function getAllImgObjet(int $idObjet): array
    {
        $stmt = $this->db->prepare("SELECT * FROM photo_objet WHERE id_objet = :id_objet");
        $stmt->execute([':id_objet' => $idObjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }   


    public function updateObjet(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE objet SET nom = :nom, description = :description, prix = :prix, etat = :etat, id_categorie = :id_categorie WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':description' => $data['description'] ?? null,
            ':prix' => $data['prix'] ?? 0,
            ':etat' => $data['etat'] ?? 0,
            ':id_categorie' => $data['id_categorie'] ?? null
        ]);
    }

    public function deleteObjet(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM objet WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

   
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT o.*, c.nom as categorie_nom FROM objet o
             LEFT JOIN categories c ON o.id_categorie = c.id
             WHERE o.id_user = :id ORDER BY o.id DESC"
        );
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getAllObjetOther(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT o.*, c.nom as categorie_nom, u.nom as user_nom FROM objet o
              JOIN categories c ON o.id_categorie = c.id
              JOIN users u ON o.id_user = u.id
             WHERE o.id_user != :id ORDER BY o.id DESC"
        );
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getAllObjetMine(int $userId): array
    {
        return $this->getByUser($userId);
    }

    public function search(?string $keyword, ?int $categoryId): array
{
    $sql = "SELECT * FROM objet WHERE 1=1";

    if (!empty($keyword)) {
        $sql .= " AND nom LIKE :kw";
    }

    if ($categoryId !== null) {
        $sql .= " AND id_categorie = :cat";
    }

    $stmt = $this->db->prepare($sql);

    if (!empty($keyword)) {
        $stmt->bindValue(':kw', "%$keyword%");
    }

    if ($categoryId !== null) {
        $stmt->bindValue(':cat', $categoryId, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function insertImgObjet($idObjet, $imgPath): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO photo_objet(id_objet, path)
         VALUES (:id_objet, :path)"
        );

         $stmt->execute([
            ':id_objet' => $idObjet,
            ':path' => $imgPath
        ]);
    }

    
}
