<?php

namespace app\models;

use Flight;
use PDO;

class DemandeModel
{
    private PDO $db;
    private $id;
    private $id_sender;
    private $id_myobjet;
    private $id_myobjetEchange;
    private $date_demande;
    private $etat;


    public function __construct($id = null, $id_sender = null, $id_myobjet = null, $id_myobjetEchange = null, $date_demande = null, $etat = null)
    {
        $this->db = Flight::db();
        $this->id = $id;
        $this->id_sender = $id_sender;
        $this->id_myobjet = $id_myobjet;
        $this->id_myobjetEchange = $id_myobjetEchange;
        $this->date_demande = $date_demande;
        $this->etat = $etat;

    }
    public function createDemande(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO demande (id_sender, id_myobjet, id_objetEchange, etat)
             VALUES (:id_sender, :id_myobjet, :id_objetEchange, :etat)"
        );

        $ok = $stmt->execute([
            ':id_sender' => $data['id_sender'],
            ':id_myobjet' => $data['id_myobjet'],
            ':id_objetEchange' => $data['id_objetEchange'],
            ':etat' => $data['etat'] ?? 'en_attente'
        ]);

        if ($ok) {
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }

    public function getListDemandeByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            "select d.id as id , o1.nom as demande,o2.nom as echange,d.date_demande,d.etat,u1.id as idProprietaire,u2.id as idDemandeur ,u2.nom as demandeur , u1.nom as proprietaire from demande d 
            join objet o2 on d.id_myobjet = o2.id 
            join objet o1 on d.id_objetEchange = o1.id 
            join users u1 on o1.id_user = u1.id 
            join users u2 on d.id_sender = u2.id 
            where u1.id = :userId"
        );

        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    public function getObjetsEnDemandeByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT id_objetEchange FROM demande 
             WHERE id_sender = :userId AND etat = 'en_attente'"
        );
        $stmt->execute([':userId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Retourne un tableau simple d'IDs
        $ids = [];
        foreach ($rows as $row) {
            $ids[] = (int) $row['id_objetEchange'];
        }
        return $ids;
    }

    public function updateEtatDemande(int $id, string $etat): bool
    {
        $stmt = $this->db->prepare("UPDATE demande SET etat = :etat WHERE id = :id");
        return $stmt->execute([':etat' => $etat, ':id' => $id]);
    }

    public function refuserAllDemandeEncourt(int $idObjet){
         $stmt = $this->db->prepare("UPDATE demande SET etat = 'refusee' WHERE id_objetEchange = :id and etat = 'en_attente'");
        return $stmt->execute([':id' => $idObjet]);
    }

    public function getIdObjetbyIdDemand(){
        
    }
}
