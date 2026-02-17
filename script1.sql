create DATABASE sinistre;
USE sinistre;

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
    id_ville INT NOT NULL,
    quantite INT NOT NULL,
    quantite_Initial INT NOT NULL,
    date_don DATETIME DEFAULT CURRENT_TIMESTAMP

);
CREATE TABLE dispatch (
    id_dispatch INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_attribuee INT NOT NULL,
    date_dispatch DATETIME DEFAULT CURRENT_TIMESTAMP
);


by categrorie

SELECT 
    c.nom_categorie,
    p.id_produit,
    p.nom_produit,
    p.prix_unitaire
FROM produit p
JOIN categorie c ON p.id_categorie = c.id_categorie
WHERE p.nom_produit != 'Argent'
ORDER BY c.nom_categorie, p.nom_produit;


// tout les dons Argent
SELECT 
    d.id_don,
    d.quantite,
    d.date_don,
    v.nom_ville
FROM don d
JOIN produit p ON d.id_produit = p.id_produit
JOIN ville v ON d.id_ville = v.id_ville
WHERE p.nom_produit = 'Argent'
ORDER BY d.date_don DESC;

// tout les dons produits
SELECT 
    d.id_don,
    d.quantite, 
    d.date_don,
    v.nom_ville
FROM don d
JOIN produit p ON d.id_produit = p.id_produit
JOIN ville v ON d.id_ville = v.id_ville
WHERE p.nom_produit != 'Argent'
ORDER BY d.date_don DESC;

//total dons Argent
SELECT 
    SUM(d.quantite) AS total_dons_argent
FROM don d
JOIN produit p ON d.id_produit = p.id_produit
WHERE p.nom_produit = 'Argent';

