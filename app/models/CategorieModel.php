<?php

namespace app\models;

use Flight;
use PDO;

class CategorieModel
{
    private PDO $db;

    public function __construct($id = null, $nom = null, $email = null, $type = null)
    {
        $this->db = Flight::db();
    }

    public function getAllCategories()
    {
        $stmt = $this->db->query("SELECT * FROM categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function renitialiser($nomFichiersql)
    {
        try {

            $sql = file_get_contents($nomFichiersql);

            if ($sql === false) {
                throw new \Exception("Impossible de lire le fichier SQL.");
            }

            $this->db->exec($sql);

            return true;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



}