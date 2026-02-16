<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Liste des catégories</h1>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nom</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $categorie): ?>
                <tr>
                    <td><?= $categorie['id'] ?></td>
                    <td><?= $categorie['nom'] ?></td>
                    <td>
                        <?php if (!empty($categorie['path'])): ?>
                            <img src="<?= $base_url ?>/public/assets/images/<?= $categorie['path'] ?>" alt="" width="100">
                        <?php else: ?>
                            (aucune image)
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="<?= $base_url ?>/deleteCategorie" method="post" style="display:inline;">
                            <input type="hidden" name="id_categorie" value="<?= $categorie['id'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                        <form action="<?= $base_url ?>/updateCategorie" method="post" style="display:inline;">
                            <input type="hidden" name="id_categorie" value="<?= $categorie['id'] ?>">
                            <input type="text" name="nom" placeholder="Nouveau nom">
                            <button type="submit">Modifier</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
</body>

</html>