<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste Besoin</title>
</head>
<body>

<h1>Liste des Besoins</h1>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID Ville</th>
            <th>ID Produit</th>
            <th>Quantité</th>
            <th>Date de saisie</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($besoins)): ?>
            <?php foreach ($besoins as $besoin): ?>
                <tr>
                    <td><?= htmlspecialchars($besoin['id_ville']) ?></td>
                    <td><?= htmlspecialchars($besoin['id_produit']) ?></td>
                    <td><?= htmlspecialchars($besoin['quantite']) ?></td>
                    <td><?= htmlspecialchars($besoin['date_saisie']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Aucun besoin enregistré</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
