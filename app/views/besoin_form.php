<?php
/**
 * Formulaire de saisie d'un besoin
 * Expects (if provided):
 * - $villes: array of ['id_ville','nom_ville']
 * - $produits: array of ['id_produit','nom_produit']
 * - $errors: array of error messages
 */
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Saisir un besoin</title>
</head>
<body>
    <h1>Saisir un besoin</h1>
    <p><a href="?page=dashboard">Retour au tableau de bord</a></p>

    <?php if (!empty($errors) && is_array($errors)): ?>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="?action=createBesoin">
        <div>
            <label for="id_ville">Ville</label>
            <select name="id_ville" id="id_ville" required>
                <option value="">-- choisir --</option>
                <?php if (!empty($villes)): foreach ($villes as $v): ?>
                    <option value="<?php echo (int)$v['id_ville']; ?>"><?php echo htmlspecialchars($v['nom_ville']); ?></option>
                <?php endforeach; endif; ?>
            </select>
        </div>

        <div>
            <label for="id_produit">Produit</label>
            <select name="id_produit" id="id_produit" required>
                <option value="">-- choisir --</option>
                <?php if (!empty($produits)): foreach ($produits as $p): ?>
                    <option value="<?php echo (int)$p['id_produit']; ?>"><?php echo htmlspecialchars($p['nom_produit']); ?></option>
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
    </form>

</body>
</html>
