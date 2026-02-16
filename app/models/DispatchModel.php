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

    public function getDispatchDetail($id_dispatch)
    {
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


    public function dispatchDon($idBesoin, $quantiteDemandee)
    {
        try {
            $this->db->beginTransaction();

            // 1️⃣ Récupérer le besoin
            $stmt = $this->db->prepare("
            SELECT id_produit, id_ville 
            FROM besoin 
            WHERE id_besoin = ?
        ");
            $stmt->execute([$idBesoin]);
            $besoin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$besoin) {
                throw new \Exception("Besoin introuvable.");
            }

            $id_produit = $besoin['id_produit'];
            $id_ville = $besoin['id_ville'];

            // 2️⃣ Vérifier total disponible
            $stmt = $this->db->prepare("
            SELECT SUM(quantite) as total_disponible
            FROM don
            WHERE id_produit = ?
            AND id_ville = ?
            AND quantite > 0
        ");
            $stmt->execute([$id_produit, $id_ville]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalDisponible = $result['total_disponible'] ?? 0;

            if ($totalDisponible < $quantiteDemandee) {
                throw new \Exception("Quantité insuffisante. Disponible : $totalDisponible");
            }

            // 3️⃣ Récupérer dons FIFO
            $stmt = $this->db->prepare("
            SELECT * FROM don
            WHERE id_produit = ?
            AND id_ville = ?
            AND quantite > 0
            ORDER BY date_don ASC
        ");
            $stmt->execute([$id_produit, $id_ville]);
            $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $quantiteRestante = $quantiteDemandee;

            foreach ($dons as $don) {

                if ($quantiteRestante <= 0)
                    break;

                $quantiteAttribuee = min($don['quantite'], $quantiteRestante);

                // Insert dispatch
                $this->db->prepare("
                INSERT INTO dispatch (id_don, id_besoin, quantite_attribuee)
                VALUES (?, ?, ?)
            ")->execute([
                            $don['id_don'],
                            $idBesoin,
                            $quantiteAttribuee
                        ]);

                // Update don
                $this->db->prepare("
                UPDATE don
                SET quantite = quantite - ?
                WHERE id_don = ?
            ")->execute([
                            $quantiteAttribuee,
                            $don['id_don']
                        ]);

                $quantiteRestante -= $quantiteAttribuee;
            }

            $this->db->commit();

            return [
                "success" => true,
                "message" => "Dispatch effectué avec succès."
            ];

        } catch (\Exception $e) {

            $this->db->rollBack();

            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * Retourne un résumé des dispatchs par ville: total besoins, total attribué, restant
     * @return array<int,array{ id_ville:int, nom_ville:string, total_besoin:int, total_attribue:int, besoin_restant:int }>
     */
    public function getDispatchSummaryByVille(): array
    {
        $sql = "SELECT v.id_ville, v.nom_ville,
                       COALESCE(SUM(b.quantite),0) AS total_besoin,
                       COALESCE(SUM(d.quantite_attribuee),0) AS total_attribue
                FROM ville v
                LEFT JOIN besoin b ON v.id_ville = b.id_ville
                LEFT JOIN dispatch d ON b.id_besoin = d.id_besoin
                GROUP BY v.id_ville, v.nom_ville
                ORDER BY v.nom_ville ASC";

        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $r) {
            $totalBesoin = (int)($r['total_besoin'] ?? 0);
            $totalAttribue = (int)($r['total_attribue'] ?? 0);
            $result[] = [
                'id_ville' => (int)$r['id_ville'],
                'nom_ville' => $r['nom_ville'],
                'total_besoin' => $totalBesoin,
                'total_attribue' => $totalAttribue,
                'besoin_restant' => max(0, $totalBesoin - $totalAttribue)
            ];
        }

        return $result;
    }





}