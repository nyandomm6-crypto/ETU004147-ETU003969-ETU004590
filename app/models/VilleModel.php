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
        
        $sql = "SELECT b.id_besoin, b.id_ville, v.nom_ville, b.quantite AS quantite_besoin,
                       COALESCE(d_sum.total_dispatch, 0) AS total_dispatch,
                       (b.quantite - COALESCE(d_sum.total_dispatch, 0)) AS reste
                FROM besoin b
                JOIN ville v ON b.id_ville = v.id_ville
                LEFT JOIN (
                    SELECT id_besoin, SUM(quantite_attribuee) AS total_dispatch
                    FROM dispatch
                    GROUP BY id_besoin
                ) d_sum ON b.id_besoin = d_sum.id_besoin
                WHERE b.id_produit = ?
                  AND b.quantite > 0
                HAVING reste > 0
                ORDER BY v.nom_ville ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_produit]);
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcul total restant
        $totalRestant = 0;
        foreach ($besoins as $b) {
            $totalRestant += (int) $b['reste'];
        }

        return [
            'besoins' => $besoins,
            'totalRestant' => $totalRestant
        ];
    }



    public function getResteBesoinParProduitParVille(): array
    {
        $sql = "SELECT bb.id_ville, v.nom_ville,
                       bb.id_produit, p.nom_produit,
                       bb.total_besoin,
                       COALESCE(dd.total_attribue, 0) AS total_attribue,
                       COALESCE(aa.total_achats, 0) AS total_achats,
                       (bb.total_besoin - COALESCE(dd.total_attribue, 0) - COALESCE(aa.total_achats, 0)) AS reste
                FROM (
                    SELECT id_ville, id_produit, SUM(quantite_max) AS total_besoin
                    FROM besoin
                    GROUP BY id_ville, id_produit
                ) bb
                LEFT JOIN (
                    SELECT b.id_ville, b.id_produit, SUM(d.quantite_attribuee) AS total_attribue
                    FROM dispatch d
                    JOIN besoin b ON d.id_besoin = b.id_besoin
                    GROUP BY b.id_ville, b.id_produit
                ) dd ON bb.id_ville = dd.id_ville AND bb.id_produit = dd.id_produit
                LEFT JOIN (
                    SELECT id_produit, SUM(quantite_achats) AS total_achats
                    FROM achat
                    GROUP BY id_produit
                ) aa ON bb.id_produit = aa.id_produit
                JOIN ville v ON bb.id_ville = v.id_ville
                JOIN produit p ON bb.id_produit = p.id_produit
                HAVING reste > 0 AND p.nom_produit !='Argent'
                ORDER BY v.nom_ville, p.nom_produit";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


  
    public function updateBesoinEpuise(): void
    {
        $sql = "UPDATE besoin b
                JOIN (
                    SELECT b2.id_besoin
                    FROM besoin b2
                    JOIN (
                        SELECT id_besoin, SUM(quantite_attribuee) AS total_dispatch
                        FROM dispatch
                        GROUP BY id_besoin
                    ) d_sum ON b2.id_besoin = d_sum.id_besoin
                    WHERE b2.quantite > 0
                      AND d_sum.total_dispatch >= b2.quantite_max
                ) epuises ON b.id_besoin = epuises.id_besoin
                SET b.quantite = 0";

        $this->db->exec($sql);
    }


    /**
     * Récapitulatif en montant (Ar) :
     * - montant_besoin_total : SUM(besoin.quantite * prix_unitaire) pour tous les besoins
     * - montant_satisfait : SUM(dispatch.quantite_attribuee * prix_unitaire)
     * - montant_restant : besoin_total - satisfait
     * Exclut les produits financiers.
     */
    public function getRecapitulatifMontant(): array
    {
        // Montant total des besoins (toutes les lignes besoin, y compris quantite=0 historique)
        $sqlBesoin = "SELECT COALESCE(SUM(b.quantite_max * p.prix_unitaire), 0) AS montant_besoin_total
                      FROM besoin b
                      JOIN produit p ON b.id_produit = p.id_produit
                      JOIN categorie c ON p.id_categorie = c.id_categorie
                      WHERE c.nom_categorie != 'Financier'";

        // Montant satisfait via dispatch
        $sqlSatisfait = "SELECT COALESCE(SUM(d.quantite_attribuee * p.prix_unitaire), 0) AS montant_satisfait
                         FROM dispatch d
                         JOIN besoin b ON d.id_besoin = b.id_besoin
                         JOIN produit p ON b.id_produit = p.id_produit
                         JOIN categorie c ON p.id_categorie = c.id_categorie
                         WHERE c.nom_categorie != 'Financier'";

        // Détail par produit
        $sqlDetail = "SELECT p.nom_produit, p.prix_unitaire,
                             COALESCE(bb.total_qte, 0) AS qte_besoin,
                             COALESCE(dd.total_attribue, 0) AS qte_satisfait,
                             (COALESCE(bb.total_qte, 0) - COALESCE(dd.total_attribue, 0)) AS qte_restant,
                             COALESCE(bb.total_qte, 0) * p.prix_unitaire AS montant_besoin,
                             COALESCE(dd.total_attribue, 0) * p.prix_unitaire AS montant_satisfait,
                             (COALESCE(bb.total_qte, 0) - COALESCE(dd.total_attribue, 0)) * p.prix_unitaire AS montant_restant
                      FROM produit p
                      JOIN categorie c ON p.id_categorie = c.id_categorie
                      LEFT JOIN (
                          SELECT id_produit, SUM(quantite_max) AS total_qte FROM besoin GROUP BY id_produit
                      ) bb ON p.id_produit = bb.id_produit
                      LEFT JOIN (
                          SELECT b.id_produit, SUM(d.quantite_attribuee) AS total_attribue
                          FROM dispatch d
                          JOIN besoin b ON d.id_besoin = b.id_besoin
                          GROUP BY b.id_produit
                      ) dd ON p.id_produit = dd.id_produit
                      WHERE c.nom_categorie != 'Financier'
                        AND COALESCE(bb.total_qte, 0) > 0
                      ORDER BY p.nom_produit";

        $stmtB = $this->db->query($sqlBesoin);
        $montantBesoinTotal = (float) $stmtB->fetchColumn();

        $stmtS = $this->db->query($sqlSatisfait);
        $montantSatisfait = (float) $stmtS->fetchColumn();

        $stmtD = $this->db->query($sqlDetail);
        $details = $stmtD->fetchAll(PDO::FETCH_ASSOC);

        return [
            'montant_besoin_total' => $montantBesoinTotal,
            'montant_satisfait' => $montantSatisfait,
            'montant_restant' => max(0, $montantBesoinTotal - $montantSatisfait),
            'details' => $details
        ];
    }





}