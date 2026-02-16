<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php if (isset($besoins) && !empty($besoins)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Ville</th>
                    <th>Produit</th>
                    <th>Quantité restante</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($besoins as $besoin): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($besoin['nom_ville'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($besoin['nom_produit'] ?? ''); ?></td>
                        <td><span class="tag tag-red"><?php echo htmlspecialchars($besoin['total_restant'] ?? ''); ?></span>
                        </td>
                        <td><a href="">Achat</a></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun besoin restant à afficher.</p>
    <?php endif; ?>
</body>

</html>