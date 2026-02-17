
INSERT INTO ville (nom_ville) VALUES
('Toamasina'),
('Mananjary'),
('Farafangana'),
('Nosy Be'),
('Morondava');

INSERT INTO categorie (nom_categorie) VALUES
('nature'),
('materiel'),
('Financier');


-- nature = 1
-- materiel = 2
-- argent = 3

INSERT INTO produit (id_categorie, prix_unitaire, nom_produit) VALUES
(1, 3000, 'Riz (kg)'),
(1, 1000, 'Eau (L)'),
(1, 6000, 'Huile (L)'),
(1, 4000, 'Haricots'),

(2, 25000, 'Tôle'),
(2, 15000, 'Bâche'),
(2, 8000, 'Clous (kg)'),
(2, 10000, 'Bois'),
(2, 6750000, 'Groupe'),

(3, 1, 'Argent');