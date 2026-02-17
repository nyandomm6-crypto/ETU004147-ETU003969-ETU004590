<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif — BNGRC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f0fe 0%, #f5f7fa 100%);
            min-height: 100vh;
            padding: 0;
        }

        /* ── Container ── */
        .container { max-width: 900px; margin: 32px auto; padding: 0 20px; }

        /* ── Cards résumé ── */
        .summary-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;
            margin-bottom: 32px;
        }
        .summary-card {
            background: #fff; border-radius: 14px; padding: 24px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06); text-align: center;
            border-top: 4px solid #cbd5e1;
            transition: transform .2s;
        }
        .summary-card:hover { transform: translateY(-2px); }
        .summary-card.besoin { border-top-color: #3b82f6; }
        .summary-card.satisfait { border-top-color: #10b981; }
        .summary-card.restant { border-top-color: #f59e0b; }
        .summary-card .label {
            font-size: 12px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 1px; color: #64748b; margin-bottom: 8px;
        }
        .summary-card .amount {
            font-size: 26px; font-weight: 800; color: #1e293b;
        }
        .summary-card .unit {
            font-size: 14px; font-weight: 400; color: #94a3b8;
        }
        .summary-card.besoin .amount { color: #2563eb; }
        .summary-card.satisfait .amount { color: #059669; }
        .summary-card.restant .amount { color: #d97706; }

        /* ── Bouton actualiser ── */
        .refresh-bar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .refresh-bar h2 { font-size: 18px; color: #1e293b; font-weight: 700; }
        .btn-refresh {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 22px; background: linear-gradient(135deg, #1a56db, #2563eb);
            color: #fff; border: none; border-radius: 10px;
            font-size: 14px; font-weight: 600; cursor: pointer;
            transition: transform .15s, box-shadow .2s;
        }
        .btn-refresh:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(26,86,219,.35); }
        .btn-refresh:active { transform: translateY(0); }
        .btn-refresh.loading { opacity: .6; pointer-events: none; }
        .btn-refresh .spinner {
            display: none; width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.3); border-top-color: #fff;
            border-radius: 50%; animation: spin .6s linear infinite;
        }
        .btn-refresh.loading .spinner { display: inline-block; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Table détail ── */
        .card {
            background: #fff; border-radius: 14px; overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #f8fafc; padding: 12px 16px;
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #64748b; text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        tbody td {
            padding: 12px 16px; font-size: 14px; color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
        tbody tr:hover { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-blue { color: #2563eb; font-weight: 600; }
        .text-green { color: #059669; font-weight: 600; }
        .text-orange { color: #d97706; font-weight: 600; }

        /* ── Updated indicator ── */
        .updated-at {
            text-align: center; margin-top: 16px;
            font-size: 12px; color: #94a3b8;
        }

        @media (max-width: 700px) {
            .summary-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <?php Flight::render('partial/header.php'); ?>

    <div class="container">

        <!-- Cards résumé -->
        <div class="summary-grid">
            <div class="summary-card besoin">
                <div class="label">Besoins totaux</div>
                <div class="amount" id="montant-besoin"><?= number_format($recap['montant_besoin_total'], 2, ',', ' ') ?></div>
                <div class="unit">Ar</div>
            </div>
            <div class="summary-card satisfait">
                <div class="label">Besoins satisfaits</div>
                <div class="amount" id="montant-satisfait"><?= number_format($recap['montant_satisfait'], 2, ',', ' ') ?></div>
                <div class="unit">Ar</div>
            </div>
            <div class="summary-card restant">
                <div class="label">Besoins restants</div>
                <div class="amount" id="montant-restant"><?= number_format($recap['montant_restant'], 2, ',', ' ') ?></div>
                <div class="unit">Ar</div>
            </div>
        </div>

        <!-- Barre avec bouton Actualiser -->
        <div class="refresh-bar">
            <h2>Détail par produit</h2>
            <button class="btn-refresh" id="btn-refresh" onclick="actualiser()">
                <span class="spinner"></span>
                🔄 Actualiser
            </button>
        </div>

        <!-- Tableau détail -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th class="text-right">Prix unit.</th>
                        <th class="text-right">Qté besoin</th>
                        <th class="text-right">Qté satisfait</th>
                        <th class="text-right">Qté restant</th>
                        <th class="text-right">Montant besoin</th>
                        <th class="text-right">Montant satisfait</th>
                        <th class="text-right">Montant restant</th>
                    </tr>
                </thead>
                <tbody id="detail-body">
                    <?php foreach ($recap['details'] as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['nom_produit']) ?></td>
                        <td class="text-right"><?= number_format($d['prix_unitaire'], 2, ',', ' ') ?></td>
                        <td class="text-right"><?= number_format($d['qte_besoin']) ?></td>
                        <td class="text-right"><?= number_format($d['qte_satisfait']) ?></td>
                        <td class="text-right"><?= number_format($d['qte_restant']) ?></td>
                        <td class="text-right text-blue"><?= number_format($d['montant_besoin'], 2, ',', ' ') ?></td>
                        <td class="text-right text-green"><?= number_format($d['montant_satisfait'], 2, ',', ' ') ?></td>
                        <td class="text-right text-orange"><?= number_format($d['montant_restant'], 2, ',', ' ') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="updated-at" id="updated-at">Dernière mise à jour : chargement initial</div>
    </div>

    <?php Flight::render('partial/footer.php'); ?>

    <script>
        const base_url = "<?= $base_url ?>";

        function formatMontant(n) {
            return parseFloat(n).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        function formatQte(n) {
            return parseInt(n).toLocaleString('fr-FR');
        }

        function actualiser() {
            const btn = document.getElementById('btn-refresh');
            btn.classList.add('loading');

            fetch(base_url + '/showTableauRecap/json')
                .then(res => { if (!res.ok) throw new Error('Erreur'); return res.json(); })
                .then(data => {
                    // Mettre à jour les cards
                    document.getElementById('montant-besoin').textContent = formatMontant(data.montant_besoin_total);
                    document.getElementById('montant-satisfait').textContent = formatMontant(data.montant_satisfait);
                    document.getElementById('montant-restant').textContent = formatMontant(data.montant_restant);

                    // Mettre à jour le tableau
                    const tbody = document.getElementById('detail-body');
                    tbody.innerHTML = '';
                    if (data.details && data.details.length > 0) {
                        data.details.forEach(function(d) {
                            const tr = document.createElement('tr');
                            tr.innerHTML =
                                '<td>' + d.nom_produit + '</td>' +
                                '<td class="text-right">' + formatMontant(d.prix_unitaire) + '</td>' +
                                '<td class="text-right">' + formatQte(d.qte_besoin) + '</td>' +
                                '<td class="text-right">' + formatQte(d.qte_satisfait) + '</td>' +
                                '<td class="text-right">' + formatQte(d.qte_restant) + '</td>' +
                                '<td class="text-right text-blue">' + formatMontant(d.montant_besoin) + '</td>' +
                                '<td class="text-right text-green">' + formatMontant(d.montant_satisfait) + '</td>' +
                                '<td class="text-right text-orange">' + formatMontant(d.montant_restant) + '</td>';
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:#94a3b8;padding:24px;">Aucun besoin enregistré</td></tr>';
                    }

                    // Heure de mise à jour
                    const now = new Date();
                    document.getElementById('updated-at').textContent = 'Dernière mise à jour : ' + now.toLocaleTimeString('fr-FR');
                })
                .catch(err => {
                    console.error(err);
                    alert('Erreur lors de l\'actualisation');
                })
                .finally(() => {
                    btn.classList.remove('loading');
                });
        }
    </script>
</body>
</html>
