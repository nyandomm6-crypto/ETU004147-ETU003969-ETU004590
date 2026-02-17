<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="<?= $base_url ?>/validateAchat" method="post">
        <label for="quantite">Quantité achat</label>
        <input type="number" id="quantite" name="quantite" min="1" required>
        <label for="frais">Frais</label>
        <input type="number" id="frais" name="frais" min="0" required>
        <input type="hidden" name="id_produit" value="<?= $id_produit ?>">
        <input type="hidden" name="id_ville" value="<?= $id_ville ?>">
        <input type="submit" value="acheter">
    </form>
    <div id="prix_estimatif"></div>

    <script>
        const base_url = "<?= $base_url ?>";
        const id_produit = <?= $id_produit ?>;

        const quantiteInput = document.getElementById('quantite');
        const fraisInput = document.getElementById('frais');
        const prixEstimatifDiv = document.getElementById('prix_estimatif');


        function calculerPrixEstimatif() {
            const quantite = parseInt(quantiteInput.value);
            const frais = parseFloat(fraisInput.value);


            if (!isNaN(quantite) && !isNaN(frais) && quantite > 0 && frais >= 0 && id_produit) {
                ajax({ quantite: quantite, frais: frais, id_produit: id_produit }, `${base_url}/prixEstimatif`)
                    .then(data => {
                        if (data && data.prix_total !== undefined) {
                            prixEstimatifDiv.textContent = `Prix estimatif total: ${data.prix_total.toFixed(2)} Ar`;
                        } else {
                            prixEstimatifDiv.textContent = 'Format de réponse invalide';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du calcul du prix estimatif:', error);
                        prixEstimatifDiv.textContent = 'Erreur lors du calcul du prix estimatif.';
                    });
            } else {
                prixEstimatifDiv.textContent = '';
            }
        }


        quantiteInput.addEventListener('input', calculerPrixEstimatif);
        fraisInput.addEventListener('input', calculerPrixEstimatif);


        calculerPrixEstimatif();


        function ajax(data, lien) {
            return fetch(lien, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
                .then(res => {
                    if (!res.ok) throw new Error(`Erreur HTTP: ${res.status}`);
                    return res.json();
                })
                .catch(error => {
                    console.error('Erreur fetch:', error);
                    throw error;
                });
        }
    </script>
</body>

</html>