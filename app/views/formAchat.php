<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achat — BNGRC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f0fe 0%, #f5f7fa 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .page-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,.10);
            padding: 40px 36px 32px;
            width: 100%;
            max-width: 520px;
        }
        .card-header { text-align: center; margin-bottom: 20px; }
        .card-header .badge {
            display: inline-block; background: #1a56db; color: #fff;
            font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; padding: 4px 14px; border-radius: 20px; margin-bottom: 10px;
        }
        .card-header h1 { font-size: 22px; color: #1e293b; font-weight: 700; }
        .budget-info {
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px;
            padding: 10px 14px; margin-bottom: 18px; font-size: 13px; color: #166534; text-align: center;
        }
        .budget-warning {
            background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
            padding: 10px 14px; margin-bottom: 18px; font-size: 13px; color: #991b1b; text-align: center;
            display: none;
        }
        .error-msg {
            background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
            padding: 10px 14px; margin-bottom: 18px; font-size: 13px; color: #991b1b; text-align: center;
        }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .form-group input {
            width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px;
            font-size: 14px; color: #1e293b; background: #f8fafc; outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-group input:focus { border-color: #1a56db; box-shadow: 0 0 0 3px rgba(26,86,219,.12); background: #fff; }
        .prix-result {
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
            padding: 12px; text-align: center; font-weight: 600; color: #1e40af; font-size: 15px;
            margin-bottom: 16px; min-height: 44px;
        }
        .btn-submit {
            width: 100%; padding: 12px; background: linear-gradient(135deg, #1a56db, #2563eb);
            color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: transform .15s, box-shadow .2s, opacity .2s;
        }
        .btn-submit:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(26,86,219,.35); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
        .back-link {
            display: block; text-align: center; margin-top: 16px; font-size: 13px;
            color: #64748b; text-decoration: none;
        }
        .back-link:hover { color: #1a56db; }
    </style>
</head>

<body>
    <?php Flight::render('partial/header.php'); ?>
    
    <div class="page-content">
    <div class="card">
        <div class="card-header">
            <span class="badge">BNGRC</span>
            <h1>Achat de produit</h1>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="budget-info" id="budget-info">Budget disponible (don argent) : chargement...</div>
        <div class="budget-warning" id="budget-warning">⚠️ Le prix estimatif dépasse le budget disponible. Achat impossible.</div>

        <form action="<?= $base_url ?>/validateAchat" method="post" id="formAchat">
            <div class="form-group">
                <label for="quantite">Quantité achat</label>
                <input type="number" id="quantite" name="quantite" min="1" required>
            </div>
            <div class="form-group">
                <label for="frais">Frais (%)</label>
                <input type="number" id="frais" name="frais" min="0" required>
            </div>
            <input type="hidden" name="id_produit" value="<?= $id_produit ?>">
            <input type="hidden" name="id_ville" value="<?= $id_ville ?>">

            <div class="prix-result" id="prix_estimatif">—</div>

            <button type="submit" class="btn-submit" id="btn-valider" disabled>Valider l'achat</button>
        </form>

        <a class="back-link" href="<?= $base_url ?>/listBesoinRestant">← Retour à la liste</a>
    </div>

    <script>
        const base_url = "<?= $base_url ?>";
        const id_produit = <?= (int)$id_produit ?>;

        const quantiteInput = document.getElementById('quantite');
        const fraisInput = document.getElementById('frais');
        const prixDiv = document.getElementById('prix_estimatif');
        const budgetInfoDiv = document.getElementById('budget-info');
        const budgetWarningDiv = document.getElementById('budget-warning');
        const btnValider = document.getElementById('btn-valider');

        let budgetDisponible = 0;
        let prixEstimatif = 0;

        // Charger le budget disponible au démarrage
        fetch(base_url + '/getDonArgent')
            .then(res => res.json())
            .then(data => {
                budgetDisponible = parseFloat(data.budget) || 0;
                budgetInfoDiv.textContent = 'Budget disponible (don argent) : ' + budgetDisponible.toFixed(2) + ' Ar';
                verifierBudget();
            })
            .catch(() => {
                budgetInfoDiv.textContent = 'Impossible de charger le budget.';
            });

        function verifierBudget() {
            if (prixEstimatif > 0 && prixEstimatif > budgetDisponible) {
                budgetWarningDiv.style.display = 'block';
                btnValider.disabled = true;
            } else if (prixEstimatif > 0) {
                budgetWarningDiv.style.display = 'none';
                btnValider.disabled = false;
            } else {
                budgetWarningDiv.style.display = 'none';
                btnValider.disabled = true;
            }
        }

        function calculerPrixEstimatif() {
            const quantite = parseInt(quantiteInput.value);
            const frais = parseFloat(fraisInput.value);

            if (!isNaN(quantite) && !isNaN(frais) && quantite > 0 && frais >= 0) {
                ajax({ quantite: quantite, frais: frais, id_produit: id_produit }, base_url + '/prixEstimatif')
                    .then(data => {
                        if (data && data.prix_total !== undefined) {
                            prixEstimatif = data.prix_total;
                            prixDiv.textContent = 'Prix estimatif : ' + prixEstimatif.toFixed(2) + ' Ar';
                            verifierBudget();
                        }
                    })
                    .catch(() => {
                        prixDiv.textContent = 'Erreur de calcul';
                        prixEstimatif = 0;
                        verifierBudget();
                    });
            } else {
                prixDiv.textContent = '—';
                prixEstimatif = 0;
                verifierBudget();
            }
        }

        quantiteInput.addEventListener('input', calculerPrixEstimatif);
        fraisInput.addEventListener('input', calculerPrixEstimatif);

        function ajax(data, lien) {
            return fetch(lien, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            }).then(res => {
                if (!res.ok) throw new Error('Erreur');
                return res.json();
            });
        }
    </script>
    </div>
</body>

</html>