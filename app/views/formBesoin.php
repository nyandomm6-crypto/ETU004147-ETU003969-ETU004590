    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <form action="/Besoin" method="post">
            <label for="id_ville">Ville:</label>
            <input type="text" id="id_ville" name="id_ville" required><br>

            <label for="id_produit">Produit:</label>
            <input type="text" id="id_produit" name="id_produit" required><br>

            <label for="quantite">Quantité:</label>
            <input type="number" id="quantite" name="quantite" required><br>

            <label for="date_saisie">Date de saisie:</label>
            <input type="date" id="date_saisie" name="date_saisie" ><br>

            <button type="submit">Envoyer</button>
        </form>
    </body>
    </html>