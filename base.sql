create database sinistre;
use sinistre;   

CREATE TABLE ville (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom_ville VARCHAR(50) NOT NULL
);

CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL
);

CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    nom_produit VARCHAR(50) NOT NULL
);

CREATE TABLE besoin (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    date_saisie DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE don (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP

);
CREATE TABLE dispatch (
    id_dispatch INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_attribuee INT NOT NULL,
    date_dispatch DATETIME DEFAULT CURRENT_TIMESTAMP
);
