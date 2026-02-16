<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes objets</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Mes objets</h1>

    <p><a href="<?php echo isset($base_url) ? $base_url : ''; ?>/accueil">Retour à l'accueil</a></p>
    <p><a href="<?php echo isset($base_url) ? $base_url : ''; ?>/objet/new">Ajouter un objet</a></p>

    <?php if (empty($objects)): ?>
        <p>Vous n'avez pas encore d'objets enregistrés.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>État</th>
                    <th>Catégorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objects as $o): ?>
                    <tr>
                        <td><?php echo ($o['id']); ?></td>
                        <td><?php echo ($o['nom']); ?></td>
                        <td><?php echo ($o['description'] ?? ''); ?></td>
                        <td><?php echo ($o['prix']); ?></td>
                        <td><?php echo ($o['etat']); ?></td>
                        <td><?php echo ($o['categorie_nom'] ?? ''); ?></td>
                        <td>
                            <a href="<?php echo isset($base_url) ? $base_url : ''; ?>/objet/edit/<?php echo $o['id']; ?>">Modifier</a>
                            |
                            <a href="<?php echo isset($base_url) ? $base_url : ''; ?>/objet/delete/<?php echo $o['id']; ?>" onclick="return confirm('Supprimer cet objet ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
