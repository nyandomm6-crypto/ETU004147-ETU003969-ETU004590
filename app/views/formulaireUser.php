<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un objet</title>
</head>

<body>
    <h1>Ajouter un objet</h1>

    <?php if (isset($error)): ?>
        <p style="color: red"><?php echo ($error); ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="<?php echo isset($base_url) ? $base_url : ''; ?>/objet/create">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description"><br>

        <label for="prix">Prix:</label>
        <input type="number" id="prix" name="prix" step="0.01" required><br>

        <label for="etat">État:</label>
        <input type="number" id="etat" name="etat" step="0.01" required><br>

        <p>Ajouter une image : <input type="file" name="img_objet" id="img_objet"></p>


        <label for="categorie">Catégorie:</label>
        <select id="categorie" name="categorie" required>

            <?php
            if (isset($categories)) {
                foreach ($categories as $categorie): ?>
                    <option value="<?php echo htmlspecialchars($categorie['id']); ?>">
                        <?php echo htmlspecialchars($categorie['nom']); ?>
                    </option>
                <?php endforeach;
            } else{?>
            <option value="">pas de categorie </option>
            <?php } ?>
        </select>

        <br>
        <button type="submit">Enregistrer</button>
    </form>

    <p><a href="<?php echo isset($base_url) ? $base_url : ''; ?>/accueil">Retour à l'accueil</a></p>
</body>

</html>