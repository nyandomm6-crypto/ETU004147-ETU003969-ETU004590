<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ajouter une catégorie</h1>
    <form action="<?= $base_url ?>/createCategorie" method="post" enctype="multipart/form-data">
        <label for="nom">Nom de la catégorie</label>
        <input type="text" name="nom" id="nom">
        <p>Ajouter une image : <input type="file" name="img_categorie" id="img_categorie"></p>
        <button type="submit">Ajouter</button>
    </form>
</body>

</html>