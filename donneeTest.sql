INSERT INTO ville (nom_ville) VALUES
('Antananarivo'),
('Toamasina'),
('Fianarantsoa'),
('Mahajanga'),
('Toliara');

INSERT INTO categorie (nom_categorie) VALUES
('Vivres'),
('Materiaux'),
('Financier');


INSERT INTO produit (id_categorie, prix_unitaire, nom_produit) VALUES
(1, 2500.00, 'Riz'),
(1, 8000.00, 'Huile'),
(1, 1200.00, 'Sucre'),

(2, 15000.00, 'Tôle'),
(2, 500.00, 'Clou'),
(2, 35000.00, 'Bois'),

(3, 1.00, 'Argent');
