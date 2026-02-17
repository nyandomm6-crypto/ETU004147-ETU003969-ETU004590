delete from besoin;
delete from don;
delete from dispatch;
delete from achat;


INSERT INTO besoin (id_ville, id_produit, quantite, date_saisie) VALUES
-- Antananarivo
(1, 1, 500, '2026-01-05 08:30:00'),  -- Riz
(1, 4, 200, '2026-01-06 10:15:00'),  -- Tôle

-- Toamasina
(2, 2, 300, '2026-01-07 09:00:00'),  -- Huile
(2, 5, 1000, '2026-01-08 14:20:00'), -- Clou

-- Fianarantsoa
(3, 3, 400, '2026-01-09 11:45:00'),  -- Sucre
(3, 6, 150, '2026-01-10 16:10:00'),  -- Bois

-- Mahajanga
(4, 1, 600, '2026-01-11 07:50:00'),  -- Riz
(4, 7, 100000, '2026-01-12 13:30:00'), -- Argent

-- Toliara
(5, 2, 250, '2026-01-13 15:00:00'),
(5, 4, 180, '2026-01-14 09:40:00');


INSERT INTO don (id_produit, quantite, quantite_Initial, date_don) VALUES
-- Dons nourriture
(1, 800, 800, '2026-01-04 08:00:00'),  -- Riz
(2, 500, 500, '2026-01-06 12:00:00'),  -- Huile
(3, 600, 600, '2026-01-07 09:30:00'),  -- Sucre

-- Dons matériaux
(4, 300, 300, '2026-01-08 10:45:00'),  -- Tôle
(5, 2000, 2000, '2026-01-09 14:00:00'), -- Clou
(6, 400, 400, '2026-01-10 16:20:00'),  -- Bois

-- Don financier
(7, 150000, 150000, '2026-01-11 11:10:00');
