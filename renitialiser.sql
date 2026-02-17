delete from besoin;
delete from don;
delete from dispatch;
delete from achat;


INSERT INTO besoin (id_ville, id_produit, quantite, quantite_max, date_saisie) VALUES
-- Antananarivo
(1, 1, 500, 500, '2026-01-05 08:30:00'),  -- Riz
(1, 4, 200, 200, '2026-01-06 10:15:00'),  -- Tôle

-- Toamasina
(2, 2, 300, 300, '2026-01-07 09:00:00'),  -- Huile
(2, 5, 1000, 1000, '2026-01-08 14:20:00'); -- Clou



INSERT INTO don (id_produit, quantite, quantite_Initial, date_don) VALUES
-- Dons nourriture
(1, 800, 800, '2026-01-04 08:00:00'),  -- Riz


-- Dons matériaux
(4, 300, 300, '2026-01-08 10:45:00'),  -- Tôle

-- Don financier
(7, 150000, 150000, '2026-01-11 11:10:00');
