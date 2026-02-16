<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if (isset($objet)) : ?>
        <h1><?php echo $objet['nom']; ?></h1>
        <p><?php echo $objet['description']; ?></p>
        <p>Prix: <?php echo $objet['prix']; ?> €</p>
        <p>État: <?php echo $objet['etat']; ?></p>
        <p>Catégorie: <?php echo $objet['categorie_nom']; ?></p>
        <p>Utilisateur: <?php echo $objet['user_nom']; ?></p>

        <?php if (isset($images) && count($images) > 0) : ?>
            <h2>Images</h2>
            <?php foreach ($images as $image) : ?>
                <img src="<?= $base_url ?>/public/assets/images/<?= $image['path'] ?>" alt="Image de l'objet">
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>