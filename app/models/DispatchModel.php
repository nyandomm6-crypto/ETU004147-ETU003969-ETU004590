<?php

namespace app\models;

use Flight;
use PDO;

class DispatchModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }


    public function createDispatch($data)
    {
        $stmt = $this->db->prepare("
        INSERT INTO dispatch (id_don, id_besoin, quantite_attribuee)
        VALUES (?, ?, ?)
    ");

        return $stmt->execute([
            $data['id_don'],
            $data['id_besoin'],
            $data['quantite_attribuee']
        ]);
    }

    public function getDispatchId($id_besoin)
    {
        $stmt = $this->db->prepare("
        SELECT *
        FROM dispatch d
        JOIN don ON d.id_don = don.id_don
        JOIN besoin b ON d.id_besoin = b.id_besoin
        JOIN produit p ON don.id_produit = p.id_produit
        JOIN ville v ON b.id_ville = v.id_ville
        WHERE d.id_besoin = ?
    ");

        $stmt->execute([$id_besoin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDispatchDetail($id_dispatch){
        $stmt = $this->db->prepare("
        SELECT *
        FROM dispatch d
        JOIN don ON d.id_don = don.id_don
        JOIN besoin b ON d.id_besoin = b.id_besoin
        JOIN produit p ON don.id_produit = p.id_produit
        JOIN ville v ON b.id_ville = v.id_ville
        WHERE d.id_dispatch = ?
    ");

        $stmt->execute([$id_dispatch]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }





}