<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau Récapitulatif — BNGRC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f9fafc;
            min-height: 100vh;
            color: #1e293b;
            line-height: 1.5;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* Section title */
        .section-title {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .section-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            background: linear-gradient(135deg, #0f172a, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-title .line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0, transparent);
        }

        /* Card */
        .card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0f4f8;
            overflow: hidden;
            margin-bottom: 32px;
        }

        .card-head {
            padding: 20px 24px;
            background: #ffffff;
            border-bottom: 1px solid #f0f4f8;
            font-weight: 600;
            font-size: 16px;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-head .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot-blue {
            background: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead th {
            padding: 16px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #fafcff;
            border-bottom: 1px solid #eef2f6;
        }

        tbody tr {
            border-bottom: 1px solid #f0f4f8;
            transition: background 0.15s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fafcff;
        }

        tbody td {
            padding: 16px 24px;
            color: #334155;
        }

        /* Tags */
        .tag {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
        }

        .tag-blue {
            background: #eef2ff;
            color: #2563eb;
        }

        .tag-green {
            background: #e6f7e6;
            color: #15803d;
        }

        .tag-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .tag-red {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Progress bar */
        .progress {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #eef2f6;
            border-radius: 100px;
            overflow: hidden;
            min-width: 80px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 100px;
        }

        .progress-fill.green {
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .progress-fill.amber {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .progress-fill.red {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }

        .progress-value {
            font-size: 12px;
            font-weight: 600;
            min-width: 40px;
            text-align: right;
        }

        /* Empty state */
        .empty {
            text-align: center;
            padding: 48px 24px;
            color: #94a3b8;
            font-size: 14px;
            background: #fafcff;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #f0f4f8;
        }

        .stat-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-value.green { color: #22c55e; }
        .stat-value.amber { color: #f59e0b; }
        .stat-value.red { color: #ef4444; }

        /* Back link */
        .back-link {
            display: inline-block;
            margin-bottom: 24px;
            font-size: 14px;
            color: #64748b;
            text-decoration: none;
        }

        .back-link:hover {
            color: #1a56db;
        }

        /* Ville group */
        .ville-group {
            margin-bottom: 8px;
        }

        .ville-name {
            font-weight: 600;
            color: #0f172a;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            thead th, tbody td {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>
    <?php Flight::render('partial/header.php'); ?>

    <div class="container">
        <a class="back-link" href="<?= $base_url ?>/">← Retour au tableau de bord</a>

        <div class="section-title">
            <h1>📊 Tableau Récapitulatif des Distributions</h1>
            <span class="line"></span>
        </div>

        <?php
        // Calculer les totaux
        $totalBesoin = 0;
        $totalAttribue = 0;
        $totalRestant = 0;
        $totalMontantBesoin = 0;
        $totalMontantAttribue = 0;

        if (!empty($recap)) {
            foreach ($recap as $row) {
                $totalBesoin += (int)($row['total_besoin'] ?? 0);
                $totalAttribue += (int)($row['total_attribue'] ?? 0);
                $restant = max(0, (int)($row['total_besoin'] ?? 0) - (int)($row['total_attribue'] ?? 0));
                $totalRestant += $restant;
                $prix = (float)($row['prix_unitaire'] ?? 0);
                $totalMontantBesoin += (int)($row['total_besoin'] ?? 0) * $prix;
                $totalMontantAttribue += (int)($row['total_attribue'] ?? 0) * $prix;
            }
        }

        $tauxCouverture = $totalBesoin > 0 ? round(($totalAttribue / $totalBesoin) * 100, 1) : 0;
        ?>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">📦 Total Besoins</div>
                <div class="stat-value"><?= number_format($totalBesoin) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">✅ Total Attribué</div>
                <div class="stat-value green"><?= number_format($totalAttribue) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">⏳ Total Restant</div>
                <div class="stat-value <?= $totalRestant > 0 ? 'red' : 'green' ?>"><?= number_format($totalRestant) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">📈 Taux de Couverture</div>
                <div class="stat-value <?= $tauxCouverture >= 80 ? 'green' : ($tauxCouverture >= 50 ? 'amber' : 'red') ?>"><?= $tauxCouverture ?>%</div>
            </div>
        </div>

        <!-- Tableau détaillé par ville et produit -->
        <div class="card">
            <div class="card-head">
                <span class="dot dot-blue"></span>
                Détail par Ville et Produit
            </div>

            <?php if (!empty($recap)): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Produit</th>
                                <th>Prix Unit.</th>
                                <th>Besoin</th>
                                <th>Attribué</th>
                                <th>Restant</th>
                                <th>Montant Besoin</th>
                                <th>Montant Attribué</th>
                                <th>Couverture</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $currentVille = null;
                            foreach ($recap as $row): 
                                $besoin = (int)($row['total_besoin'] ?? 0);
                                $attribue = (int)($row['total_attribue'] ?? 0);
                                $restant = max(0, $besoin - $attribue);
                                $prix = (float)($row['prix_unitaire'] ?? 0);
                                $montantBesoin = $besoin * $prix;
                                $montantAttribue = $attribue * $prix;
                                $pourcent = $besoin > 0 ? round(($attribue / $besoin) * 100, 1) : 0;
                                $progressClass = $pourcent >= 80 ? 'green' : ($pourcent >= 50 ? 'amber' : 'red');
                            ?>
                                <tr>
                                    <td>
                                        <?php if ($currentVille !== $row['nom_ville']): ?>
                                            <span class="ville-name"><?= htmlspecialchars($row['nom_ville'] ?? '') ?></span>
                                            <?php $currentVille = $row['nom_ville']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= htmlspecialchars($row['nom_produit'] ?? '') ?></strong></td>
                                    <td><?= number_format($prix, 2) ?> Ar</td>
                                    <td><span class="tag tag-blue"><?= number_format($besoin) ?></span></td>
                                    <td><span class="tag tag-green"><?= number_format($attribue) ?></span></td>
                                    <td>
                                        <?php if ($restant > 0): ?>
                                            <span class="tag tag-red"><?= number_format($restant) ?></span>
                                        <?php else: ?>
                                            <span class="tag tag-green">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($montantBesoin, 2) ?> Ar</td>
                                    <td><?= number_format($montantAttribue, 2) ?> Ar</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar">
                                                <div class="progress-fill <?= $progressClass ?>" style="width: <?= min(100, $pourcent) ?>%"></div>
                                            </div>
                                            <span class="progress-value"><?= $pourcent ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot style="background: #fafcff; font-weight: 600;">
                            <tr>
                                <td colspan="3"><strong>TOTAL</strong></td>
                                <td><span class="tag tag-blue"><?= number_format($totalBesoin) ?></span></td>
                                <td><span class="tag tag-green"><?= number_format($totalAttribue) ?></span></td>
                                <td><span class="tag <?= $totalRestant > 0 ? 'tag-red' : 'tag-green' ?>"><?= number_format($totalRestant) ?></span></td>
                                <td><?= number_format($totalMontantBesoin, 2) ?> Ar</td>
                                <td><?= number_format($totalMontantAttribue, 2) ?> Ar</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar">
                                            <div class="progress-fill <?= $tauxCouverture >= 80 ? 'green' : ($tauxCouverture >= 50 ? 'amber' : 'red') ?>" style="width: <?= min(100, $tauxCouverture) ?>%"></div>
                                        </div>
                                        <span class="progress-value"><?= $tauxCouverture ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <p class="empty">Aucune donnée de distribution disponible. Effectuez d'abord des dispatches.</p>
            <?php endif; ?>
        </div>

    </div>

    <?php Flight::render('partial/footer.php'); ?>
</body>
</html>
