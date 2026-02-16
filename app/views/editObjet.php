<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un objet</title>
</head>
<body>
    <h1>Modifier un objet</h1>

   

    <form method="post" enctype="multipart/form-data" action="<?php echo isset($base_url) ? $base_url : ''; ?>/objet/update/<?php echo ($objet['id']); ?>">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo ($objet['nom']); ?>" required><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?php echo ($objet['description'] ); ?>"><br>

        <label for="prix">Prix:</label>
        <input type="number" id="prix" name="prix"  value="<?php echo ($objet['prix'] ); ?>" required><br>

        <label for="etat">État:</label>
        <input type="number" id="etat" name="etat"  value="<?php echo ($objet['etat'] ); ?>" required><br>

        <p>Ajouter une image : <input type="file" name="img_objet" id="img_objet"></p>


        <label for="categorie">Catégorie:</label>
        <select id="categorie" name="categorie" required>

            <?php if (!empty($categories)):
                foreach ($categories as $c): ?>
                    <option value="<?php echo ($c['id']); ?>" <?php echo ($c['id'] == ($objet['id_categorie'] ?? null)) ? 'selected' : ''; ?>><?php echo ($c['nom']); ?></option>
                <?php endforeach;
            else: ?>
                <option value="">(Aucune catégorie)</option>
            <?php endif; ?>
        </select>

        <br>
        <button type="submit">Enregistrer</button>
    </form>

    <p><a href="<?php echo isset($base_url) ? $base_url : ''; ?>/objet/mine">Retour à mes objets</a></p>
</body>
</html>
