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
            <th>ID</th>
            <th>Ville</th>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Date de saisie</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($besoins as $besoin): ?>
            <tr>
                <td><?= $besoin['id'] ?></td>
                <td><?= $besoin['ville'] ?></td>
                <td><?= $besoin['produit'] ?></td>
                <td><?= $besoin['quantite'] ?></td>
                <td><?= $besoin['date_saisie'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
