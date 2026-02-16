<?php

namespace app\models;

use Flight;
use PDO;

class HistoModel
{
    private PDO $db;
    private $id;
    private $id_user;
    private $id_objet;
    private $date_acquisition;



    public function __construct($id = null, $id_user = null, $id_objet = null, $date_acquisition = null)
    {
        $this->db = Flight::db();
        $this->id = $id;
        $this->id_user = $id_user;
        $this->id_objet = $id_objet;
        $this->date_acquisition = $date_acquisition;

    }
    public function createHisto(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO demande (id_user,id_objet)
         VALUES (:id_user,:id_objet)"
        );

        $ok = $stmt->execute([
            ':id_user' => $data['id_user'],
            ':id_objet' => $data['id_objet']
        ]);

        if ($ok) {
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }

    public function getAllHisto()
    {
        $stmt = $this->db->prepare("select count(id) as countHisto,o.nom,o.description,o.prix,o.etat,c.nom,u.nom,h.date_acquisition from histo_appartenance h 
        join objet o on h.id_objet = o.id 
        join categorie c on o.id_categorie = c.id 
        join users u on h.id_user = u.id ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
