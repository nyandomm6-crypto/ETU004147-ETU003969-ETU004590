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

            $stmt = $this->db->prepare("
            SELECT SUM(quantite) as total_disponible
            FROM don
            WHERE id_produit = ?
            AND quantite > 0
        ");
            $stmt->execute([$id_produit]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalDisponible = $result['total_disponible'] ?? 0;

            if ($totalDisponible < $quantiteDemandee) {
                throw new \Exception("Quantité insuffisante. Disponible : $totalDisponible");
            }

            // 3️⃣ Récupérer dons FIFO
            $stmt = $this->db->prepare("
            SELECT * FROM don
            WHERE id_produit = ?
            AND quantite > 0
            ORDER BY date_don ASC
        ");
            $stmt->execute([$id_produit]);
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

   
    public function getDispatchSummaryByVille(): array
    {
        $sql = "SELECT v.id_ville, v.nom_ville,
                       COALESCE(SUM(b.quantite_max),0) AS total_besoin,
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


    public function createDispatch($idDon, $idBesoin, $quantiteAttribuee)
    {
        $sql = "INSERT INTO dispatch (id_don, id_besoin, quantite_attribuee) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idDon, $idBesoin, $quantiteAttribuee]);
    }

    /**
     * Récupère le stock de don disponible pour un produit donné
     */
    public function getDonRestantByProduit(int $id_produit): int
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(quantite), 0) AS total
            FROM don
            WHERE id_produit = ? AND quantite > 0
        ");
        $stmt->execute([$id_produit]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Récupère les besoins restants pour un produit avec ordre par date de saisie (le plus ancien en premier)
     */
    public function getBesoinsParDateSaisie(int $id_produit): array
    {
        $sql = "SELECT b.id_besoin, b.id_ville, v.nom_ville, b.quantite AS quantite_besoin,
                       COALESCE(d_sum.total_dispatch, 0) AS total_dispatch,
                       (b.quantite - COALESCE(d_sum.total_dispatch, 0)) AS reste,
                       b.date_saisie
                FROM besoin b
                JOIN ville v ON b.id_ville = v.id_ville
                LEFT JOIN (
                    SELECT id_besoin, SUM(quantite_attribuee) AS total_dispatch
                    FROM dispatch
                    GROUP BY id_besoin
                ) d_sum ON b.id_besoin = d_sum.id_besoin
                WHERE b.id_produit = ?
                HAVING reste > 0
                ORDER BY b.date_saisie ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_produit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les besoins restants pour un produit avec ordre par quantité (le plus petit en premier)
     */
    public function getBesoinsParQuantitePlusPetit(int $id_produit): array
    {
        $sql = "SELECT b.id_besoin, b.id_ville, v.nom_ville, b.quantite AS quantite_besoin,
                       COALESCE(d_sum.total_dispatch, 0) AS total_dispatch,
                       (b.quantite - COALESCE(d_sum.total_dispatch, 0)) AS reste,
                       b.date_saisie
                FROM besoin b
                JOIN ville v ON b.id_ville = v.id_ville
                LEFT JOIN (
                    SELECT id_besoin, SUM(quantite_attribuee) AS total_dispatch
                    FROM dispatch
                    GROUP BY id_besoin
                ) d_sum ON b.id_besoin = d_sum.id_besoin
                WHERE b.id_produit = ?
                HAVING reste > 0
                ORDER BY reste ASC, b.date_saisie ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_produit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Distribution automatique par date de saisie (FIFO - le plus ancien d'abord)
     */
    public function dispatchAutomatiqueParDate(int $id_produit, int $quantiteADistribuer): array
    {
        try {
            $this->db->beginTransaction();

            $besoins = $this->getBesoinsParDateSaisie($id_produit);
            $donRestant = $this->getDonRestantByProduit($id_produit);
            $quantiteDisponible = min($donRestant, $quantiteADistribuer);

            if ($quantiteDisponible <= 0) {
                throw new \Exception("Aucun don disponible pour ce produit.");
            }

            $distributions = [];
            $quantiteRestante = $quantiteDisponible;

            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) break;

                $aAttribuer = min((int)$besoin['reste'], $quantiteRestante);
                $this->attribuerDonABesoin($id_produit, $besoin['id_besoin'], $aAttribuer);

                $distributions[] = [
                    'id_besoin' => $besoin['id_besoin'],
                    'nom_ville' => $besoin['nom_ville'],
                    'quantite_attribuee' => $aAttribuer,
                    'date_saisie' => $besoin['date_saisie']
                ];

                $quantiteRestante -= $aAttribuer;
            }

            $this->db->commit();
            return ['success' => true, 'distributions' => $distributions, 'message' => 'Distribution par date effectuée.'];

        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Distribution automatique par plus petite quantité d'abord
     */
    public function dispatchAutomatiqueParPlusPetit(int $id_produit, int $quantiteADistribuer): array
    {
        try {
            $this->db->beginTransaction();

            $besoins = $this->getBesoinsParQuantitePlusPetit($id_produit);
            $donRestant = $this->getDonRestantByProduit($id_produit);
            $quantiteDisponible = min($donRestant, $quantiteADistribuer);

            if ($quantiteDisponible <= 0) {
                throw new \Exception("Aucun don disponible pour ce produit.");
            }

            $distributions = [];
            $quantiteRestante = $quantiteDisponible;

            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) break;

                $aAttribuer = min((int)$besoin['reste'], $quantiteRestante);
                $this->attribuerDonABesoin($id_produit, $besoin['id_besoin'], $aAttribuer);

                $distributions[] = [
                    'id_besoin' => $besoin['id_besoin'],
                    'nom_ville' => $besoin['nom_ville'],
                    'quantite_attribuee' => $aAttribuer,
                    'reste_besoin' => $besoin['reste']
                ];

                $quantiteRestante -= $aAttribuer;
            }

            $this->db->commit();
            return ['success' => true, 'distributions' => $distributions, 'message' => 'Distribution par plus petit effectuée.'];

        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Distribution automatique par proportionnalité
     * Chaque ville reçoit une part proportionnelle à son besoin restant
     * Utilise toujours la partie entière (floor) : 0.9 → 0, 1.8 → 1
     */
    public function dispatchAutomatiqueParProportionnalite(int $id_produit, int $quantiteADistribuer): array
    {
        try {
            $this->db->beginTransaction();

            $besoins = $this->getBesoinsParDateSaisie($id_produit);
            $donRestant = $this->getDonRestantByProduit($id_produit);
            $quantiteDisponible = min($donRestant, $quantiteADistribuer);

            if ($quantiteDisponible <= 0) {
                throw new \Exception("Aucun don disponible pour ce produit.");
            }

            // Calculer le total des besoins restants
            $totalBesoinsRestants = 0;
            foreach ($besoins as $besoin) {
                $totalBesoinsRestants += (int)$besoin['reste'];
            }

            if ($totalBesoinsRestants <= 0) {
                throw new \Exception("Aucun besoin restant pour ce produit.");
            }

            $distributions = [];
            $totalDistribue = 0;

            foreach ($besoins as $besoin) {
                $reste = (int)$besoin['reste'];
                
                // Calcul proportionnel avec partie entière (floor)
                // Ex: (50 / 200) * 100 = 25.0 → 25
                // Ex: (30 / 200) * 100 = 15.0 → 15
                // Ex: (18 / 200) * 100 = 9.0 → 9
                $partProportionnelle = (int) floor(($reste / $totalBesoinsRestants) * $quantiteDisponible);
                
                // Ne pas dépasser le besoin restant
                $aAttribuer = min($partProportionnelle, $reste);

                if ($aAttribuer > 0) {
                    $this->attribuerDonABesoin($id_produit, $besoin['id_besoin'], $aAttribuer);

                    $distributions[] = [
                        'id_besoin' => $besoin['id_besoin'],
                        'nom_ville' => $besoin['nom_ville'],
                        'quantite_attribuee' => $aAttribuer,
                        'pourcentage' => round(($reste / $totalBesoinsRestants) * 100, 1)
                    ];

                    $totalDistribue += $aAttribuer;
                }
            }

            // Calculer le reste non distribué (dû aux arrondis floor)
            $resteNonDistribue = $quantiteDisponible - $totalDistribue;

            $this->db->commit();
            
            $message = "Distribution proportionnelle effectuée. Total distribué: $totalDistribue";
            if ($resteNonDistribue > 0) {
                $message .= " (reste non distribué dû aux arrondis: $resteNonDistribue)";
            }
            
            return [
                'success' => true, 
                'distributions' => $distributions, 
                'message' => $message,
                'total_distribue' => $totalDistribue,
                'reste_non_distribue' => $resteNonDistribue
            ];

        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Méthode interne pour attribuer un don à un besoin (FIFO sur les dons)
     */
    private function attribuerDonABesoin(int $id_produit, int $id_besoin, int $quantite): void
    {
        // Récupérer les dons disponibles FIFO
        $stmt = $this->db->prepare("
            SELECT * FROM don
            WHERE id_produit = ? AND quantite > 0
            ORDER BY date_don ASC
        ");
        $stmt->execute([$id_produit]);
        $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $quantiteRestante = $quantite;

        foreach ($dons as $don) {
            if ($quantiteRestante <= 0) break;

            $aUtiliser = min((int)$don['quantite'], $quantiteRestante);

            // Insérer le dispatch
            $this->db->prepare("
                INSERT INTO dispatch (id_don, id_besoin, quantite_attribuee)
                VALUES (?, ?, ?)
            ")->execute([$don['id_don'], $id_besoin, $aUtiliser]);

            // Mettre à jour le don
            $this->db->prepare("
                UPDATE don SET quantite = quantite - ? WHERE id_don = ?
            ")->execute([$aUtiliser, $don['id_don']]);

            $quantiteRestante -= $aUtiliser;
        }
    }

    /**
     * Récupère le tableau récapitulatif des dispatches par ville et produit
     */
    public function getTableauRecapitulatif(): array
    {
        $sql = "SELECT 
                    v.id_ville,
                    v.nom_ville,
                    p.id_produit,
                    p.nom_produit,
                    p.prix_unitaire,
                    COALESCE(SUM(b.quantite_max), 0) AS total_besoin,
                    COALESCE(SUM(d.quantite_attribuee), 0) AS total_attribue,
                    (COALESCE(SUM(b.quantite_max), 0) - COALESCE(SUM(d.quantite_attribuee), 0)) AS besoin_restant
                FROM ville v
                LEFT JOIN besoin b ON v.id_ville = b.id_ville
                LEFT JOIN produit p ON b.id_produit = p.id_produit
                LEFT JOIN dispatch d ON b.id_besoin = d.id_besoin
                WHERE b.id_besoin IS NOT NULL
                GROUP BY v.id_ville, v.nom_ville, p.id_produit, p.nom_produit, p.prix_unitaire
                ORDER BY v.nom_ville, p.nom_produit";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}