<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Besoins restants — BNGRC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f0fe 0%, #f5f7fa 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #1e293b;
        }

        .container {
            flex: 1;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            padding: 32px 20px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .section-title h2 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }

        .section-title .line {
            flex: 1;
            height: 2px;
            background: #cbd5e1;
            border-radius: 1px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .card-head {
            padding: 16px 22px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 700;
            font-size: 15px;
            color: #334155;
        }

        .card-head .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
            background: #f59e0b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f1f5f9;
        }

        thead th {
            padding: 12px 18px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background .12s;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody td {
            padding: 12px 18px;
            font-size: 14px;
            color: #334155;
        }

        .tag {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .tag-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .tag-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .tag-green {
            background: #dcfce7;
            color: #15803d;
        }

        .btn-achat {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1a56db, #2563eb);
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: transform .15s, box-shadow .2s;
        }

        .btn-achat:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(26, 86, 219, 0.35);
        }

        .empty {
            text-align: center;
            padding: 32px;
            color: #94a3b8;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <?php Flight::render('partial/header.php'); ?>

    <div class="container">
        <div class="section-title">
            <h2>Reste des besoins</h2><span class="line"></span>
        </div>
        <div>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-head"><span class="dot"></span> Quantités encore nécessaires</div>

            <?php if (!empty($besoins)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Ville</th>
                            <th>Produit</th>
                            <th>Total besoin</th>
                            <th>Déjà attribué</th>
                            <th>Reste</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($besoins as $b): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['nom_ville'] ?? '') ?></td>
                                <td><?= htmlspecialchars($b['nom_produit'] ?? '') ?></td>
                                <td><span class="tag tag-blue"><?= (int) ($b['total_besoin'] ?? 0) ?></span></td>
                                <td><span class="tag tag-green"><?= (int) ($b['total_attribue'] ?? 0) ?></span></td>
                                <td><span class="tag tag-red"><?= (int) ($b['reste'] ?? 0) ?></span></td>
                                <form action="<?= $base_url ?>/showFormAchat" method="post">
                                    <input type="hidden" name="id_ville" value="<?= (int) ($b['id_ville'] ?? 0) ?>">
                                    <input type="hidden" name="id_produit" value="<?= (int) ($b['id_produit'] ?? 0) ?>">
                                    <input type="hidden" name="reste" value="<?= (int) ($b['reste'] ?? 0) ?>">   
                                    <td><input type="submit" value="Simuler le résultat"></td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="empty">Aucun besoin restant à afficher.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php Flight::render('partial/footer.php'); ?>
</body>

</html>