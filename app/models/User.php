<?php

namespace app\models;

use Flight;
use PDO;

class User
{
    private PDO $db;
    private $id;
    private $nom;

    private $email;
    private $type;

    public function __construct($id = null, $nom = null, $email = null, $type = null)
    {
        $this->db = Flight::db();
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->type = $type;
    }
    public function __sleep()
    {
        return ['id', 'nom', 'email', 'type'];
    }

    public function __wakeup()
    {
        $this->db = Flight::db();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function createUser(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password, email, type)
         VALUES (:username, :password, :email, :type)"
        );

        $ok = $stmt->execute([
            ':username' => $data['username'],
            ':password' => $data['password'],
            ':email' => $data['email'],
            ':type' => $data['type']
        ]);

        if ($ok) {
            return (int) $this->db->lastInsertId();
        }

        return 0;
    }

    public function emailExist(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }



    public function validatePassword($data)
    {
        $errors = [];

        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirmPassword'] ?? '';

        if ($password !== $confirmPassword) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if (!preg_match("/[A-Z]/", $password)) {
            $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
        }

        if (!preg_match("/[0-9]/", $password)) {
            $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
        }

        if (!preg_match("/[\W_]/", $password)) {
            $errors[] = "Le mot de passe doit contenir au moins un caractère spécial.";
        }

        return $errors;
    }



    public function validateEmail($data)
    {
        $errors = [];
        $email = trim($data['email'] ?? '');

        if (empty($email)) {
            $errors[] = "Email obligatoire.";
            return $errors;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email invalide.";
        }

        if ($this->emailExist($email)) {
            $errors[] = "Email efa misy.";
        }

        return $errors;
    }

    public function validateName($data)
    {
        $errors = [];
        $name = trim($data['nom'] ?? '');

        if (empty($name)) {
            $errors[] = "Nom obligatoire.";
            return $errors;
        }

        if (strlen($name) < 3) {
            $errors[] = "Le nom doit contenir au moins 3 caractères.";
        }

        if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $name)) {
            $errors[] = "Le nom ne doit pas contenir de caractères spéciaux.";
        }

        return $errors;
    }


    public function signUp(array $data): array
    {
        $errors = [];

        // Validations
        $errors = array_merge($errors, $this->validateName($data));
        $errors = array_merge($errors, $this->validateEmail($data));
        $errors = array_merge($errors, $this->validatePassword($data));

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        // Hash du mot de passe
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Insertion
        $stmt = $this->db->prepare("
        INSERT INTO users (nom, email, password, type)
        VALUES (:nom, :email, :password, :type)
    ");

        $ok = $stmt->execute([
            ':nom' => $data['nom'],
            ':email' => $data['email'],
            ':password' => $hashedPassword,
            ':type' => 'user'
        ]);

        if (!$ok) {
            return [
                'success' => false,
                'errors' => ['Erreur lors de la création du compte.']
            ];
        }

        return [
            'success' => true
        ];
    }


    public function login(array $data): array
    {
        $errors = [];

        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($email)) {
            $errors[] = "Email obligatoire.";
        }

        if (empty($password)) {
            $errors[] = "Mot de passe obligatoire.";
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return [
                'success' => false,
                'errors' => ['Email ou mot de passe incorrect.']
            ];
        }

        $useraa = new User($user['id'], $user['nom'], $user['email'], $user['type']);
        $estAdmin = $user['type'] === 'admin';
        return [
            'success' => true,
            'user' => serialize($useraa),
            'estAdmin' => $estAdmin
        ];
    }


    public function getAllUserInscrit()
    {
        $stmt = $this->db->query("SELECT count(id) as countUser ,nom FROM users where type != 'admin'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
