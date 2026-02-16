<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="<?= $base_url ?>/createAchat" method="post">
        <label for="quantite">Quantité achat</label>
        <input type="number" id="quantite" name="quantite" min="1" required>
        <label for="frais">Frais</label>
        <input type="number" id="frais" name="frais" min="0" required>
        <input type="submit" value="acheter">
    </form>
    <div id="prix_estimatif"></div>
    <script>
        const quantiteInput = document.getElementById('quantite');
        const fraisInput = document.getElementById('frais');
        const prixEstimatifDiv = document.getElementById('prix_estimatif');

        quantiteInput.addEventListener('change', function () {
            const quantite = parseFloat(this.value);
            const frais = parseFloat(fraisInput.value) || 0;
            const id_produit = document.getElementById('id_produit').value;

            if (quantite > 0 && id_produit) {
                fetch(`${base_url}/getPrixUnitaire?id_produit=${id_produit}`)
                    .then(response => response.json())
                    .then(data => {
                        const prixUnitaire = parseFloat(data.prix_unitaire);
                        const prixTotal = (prixUnitaire * quantite) + frais;
                        prixEstimatifDiv.textContent = `Prix estimatif total: ${prixTotal.toFixed(2)} FCFA`;
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération du prix unitaire:', error);
                        prixEstimatifDiv.textContent = 'Erreur lors du calcul du prix estimatif.';
                    });
            } else {
                prixEstimatifDiv.textContent = '';
            }
        });

    </script>
</body>

</html>