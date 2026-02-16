<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="/Besoin" method="post">

        <div>
            <label for="id_ville">Ville</label>
            <select name="id_ville" id="id_ville" required>
                <option value="">-- choisir --</option>
                <?php if (!empty($villes)):
                    foreach ($villes as $v): ?>
                        <option value="<?php echo (int) $v['id_ville']; ?>">
                            <?php echo htmlspecialchars($v['nom_ville']); ?>
                        </option>
                    <?php endforeach; endif; ?>
            </select>
        </div>

        <div>
            <label for="id_produit">Produit</label>
            <select name="id_produit" id="id_produit" required>
                <option value="">-- choisir --</option>
                <?php if (!empty($produits)):
                    foreach ($produits as $p): ?>
                        <option value="<?php echo (int) $p['id_produit']; ?>">
                            <?php echo htmlspecialchars($p['nom_produit']); ?>
                        </option>
                    <?php endforeach; endif; ?>
            </select>
        </div>

        <div>
            <label for="quantite">Quantité</label>
            <input type="number" name="quantite" id="quantite" min="1" required>
        </div>

        <div>
            <button type="submit">Enregistrer le besoin</button>
        </div>

        <button type="submit">Envoyer</button>
    </form>
</body>

</html>