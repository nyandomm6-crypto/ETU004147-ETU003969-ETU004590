<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord — BNGRC</title>
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

        /* Typography */
        h1, h2, h3 {
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        /* ── Container ───────────────────────── */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ── Stats Grid ──────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0f4f8;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }

        .stat-label {
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            font-size: 13px;
            color: #22c55e;
        }

        /* ── Section titles ───────────────────── */
        .section-title {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            margin-top: 48px;
        }

        .section-title:first-of-type {
            margin-top: 0;
        }

        .section-title h2 {
            font-size: 24px;
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

        /* ── Cards ────────────────────────────── */
        .card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0f4f8;
            overflow: hidden;
            margin-bottom: 32px;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
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

        .dot-green {
            background: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .dot-amber {
            background: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        /* ── Tables ───────────────────────────── */
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

        /* ── Tags / Badges ────────────────────── */
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

        /* ── Empty state ──────────────────────── */
        .empty {
            text-align: center;
            padding: 48px 24px;
            color: #94a3b8;
            font-size: 14px;
            background: #fafcff;
            border-radius: 16px;
            margin: 0;
        }

        /* ── Progress bar ─────────────────────── */
        .progress {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-bar {
            flex: 1;
            height: 6px;
            background: #eef2f6;
            border-radius: 100px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            border-radius: 100px;
            width: 0%;
        }

        .progress-value {
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            min-width: 45px;
        }

        /* ── Summary cards ────────────────────── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 24px;
        }

        .summary-item {
            background: #fafcff;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #eef2f6;
        }

        .summary-city {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 16px;
            font-size: 16px;
        }

        .summary-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
            color: #475569;
        }

        /* ── Responsive ───────────────────────── */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .card-head {
                padding: 16px 20px;
            }
            
            tbody td, thead th {
                padding: 12px 16px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .stat-card {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body>

    <!-- ── Top bar ─────────────────────────── -->
    <?php Flight::render('partial/header.php'); ?>

    <div class="container">

        <!-- ── Quick Stats ─────────────────────── -->
        <div class="stats-grid">
            <?php 
            $total_villes = is_array($villes) ? count($villes) : 0;
            $total_besoins = 0;
            $total_dons = is_array($dons) ? count($dons) : 0;
            
            if (!empty($villes) && is_array($villes)) {
                foreach ($villes as $ville) {
                    $total_besoins += isset($ville['besoins']) ? count($ville['besoins']) : 0;
                }
            }
            ?>
            <div class="stat-card">
                <div class="stat-label">Villes actives</div>
                <div class="stat-value"><?php echo $total_villes; ?></div>
                <div class="stat-trend">
                    <span>📍</span>
                    <span>Centres de distribution</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Besoins en cours</div>
                <div class="stat-value"><?php echo $total_besoins; ?></div>
                <div class="stat-trend">
                    <span>📦</span>
                    <span>Articles nécessaires</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Dons reçus</div>
                <div class="stat-value"><?php echo $total_dons; ?></div>
                <div class="stat-trend">
                    <span>🎁</span>
                    <span>Contributions enregistrées</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Taux de couverture</div>
                <div class="stat-value">
                    <?php 
                    $couverture = 0;
                    if ($total_besoins > 0 && !empty($dispatches)) {
                        $total_attribue = 0;
                        foreach ($dispatches as $d) {
                            $total_attribue += intval($d['quantite_attribuee'] ?? 0);
                        }
                        $couverture = min(100, round(($total_attribue / $total_besoins) * 100));
                    }
                    echo $couverture . '%';
                    ?>
                </div>
                <div class="progress" style="margin-top: 12px;">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $couverture; ?>%"></div>
                    </div>
                    <span class="progress-value"><?php echo $couverture; ?>%</span>
                </div>
            </div>
        </div>

        <!-- ── Villes et besoins ────────────────── -->
        <div class="section-title">
            <h2>Villes et besoins</h2>
            <span class="line"></span>
        </div>

        <?php if (!empty($villes) && is_array($villes)): ?>
            <?php foreach ($villes as $ville): ?>
                <div class="card">
                    <div class="card-head">
                        <span class="dot dot-blue"></span>
                        <?php echo htmlspecialchars($ville['nom_ville'] ?? 'Ville inconnue'); ?>
                        <?php if (!empty($ville['besoins'])): ?>
                            <span class="tag tag-blue" style="margin-left: auto;">
                                <?php echo count($ville['besoins']); ?> besoin(s)
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($ville['besoins'])): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité</th>
                                        <th>Valeur totale</th>
                                        <th>Date saisie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ville['besoins'] as $besoin): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($besoin['nom_produit'] ?? ''); ?></strong></td>
                                            <td><?php echo number_format((float)($besoin['prix_unitaire'] ?? 0), 2); ?> €</td>
                                            <td><span class="tag tag-blue"><?php echo htmlspecialchars($besoin['quantite'] ?? ''); ?></span></td>
                                            <td><?php $total = (float)($besoin['prix_unitaire'] ?? 0) * (int)($besoin['quantite'] ?? 0); echo number_format($total,2); ?> €</td>
                                            <td><?php echo htmlspecialchars($besoin['date_saisie'] ?? ''); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="empty">Aucun besoin saisi pour cette ville.</p>
                    <?php endif; ?>

                    <?php if (!empty($ville['dons'])): ?>
                        <div class="card-head" style="border-top:1px solid #f0f4f8;">
                            <span class="dot dot-green"></span> 
                            Dons reçus
                            <span class="tag tag-green" style="margin-left: auto;">
                                <?php echo count($ville['dons']); ?> don(s)
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Date don</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ville['dons'] as $don): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($don['nom_produit'] ?? ''); ?></td>
                                            <td><span class="tag tag-green"><?php echo htmlspecialchars($don['quantite'] ?? ''); ?></span></td>
                                            <td><?php echo htmlspecialchars($don['date_don'] ?? ''); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card"><p class="empty">Aucune ville disponible. Veuillez saisir des villes et besoins.</p></div>
        <?php endif; ?>


        <!-- ── Liste des dons ─────────────── -->
        <div class="section-title">
            <h2>Dons</h2>
            <span class="line"></span>
        </div>

        <div class="card">
            <?php if (!empty($dons) && is_array($dons)): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Date don</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dons as $don): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($don['nom_ville'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($don['nom_produit'] ?? ''); ?></td>
                                    <td><span class="tag tag-green"><?php echo htmlspecialchars($don['quantite'] ?? ''); ?></span></td>
                                    <td><?php echo htmlspecialchars($don['date_don'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="empty">Aucun don enregistré.</p>
            <?php endif; ?>
        </div>

        <!-- ── Résultats du dispatch ───────── -->
        <?php if (!empty($dispatches) && is_array($dispatches)): ?>
            <div class="section-title">
                <h2>Résultats du dispatch</h2>
                <span class="line"></span>
            </div>
            
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Ville</th>
                                <th>Produit</th>
                                <th>Don (ID)</th>
                                <th>Besoin (ID)</th>
                                <th>Qté attribuée</th>
                                <th>Date dispatch</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dispatches as $d): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($d['nom_ville'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($d['nom_produit'] ?? ''); ?></td>
                                    <td><span class="tag tag-amber">#<?php echo htmlspecialchars($d['id_don'] ?? ''); ?></span></td>
                                    <td><span class="tag tag-blue">#<?php echo htmlspecialchars($d['id_besoin'] ?? ''); ?></span></td>
                                    <td><span class="tag tag-green"><?php echo htmlspecialchars($d['quantite_attribuee'] ?? ''); ?></span></td>
                                    <td><?php echo htmlspecialchars($d['date_dispatch'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </div>
    
    <?php Flight::render('partial/footer.php'); ?>

</body>
</html>