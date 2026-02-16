<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des dons — BNGRC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f0fe 0%, #f5f7fa 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .page-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .page-header .badge {
            display: inline-block;
            background: #1a56db;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        .page-header h1 {
            font-size: 26px;
            color: #1e293b;
            font-weight: 700;
        }
        .page-header p {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .card-toolbar .count {
            font-size: 13px;
            color: #64748b;
        }
        .card-toolbar .count strong {
            color: #1a56db;
        }
        .btn-add {
            display: inline-block;
            padding: 8px 18px;
            background: linear-gradient(135deg, #1a56db, #2563eb);
            color: #fff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: transform .15s, box-shadow .2s;
        }
        .btn-add:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(26,86,219,0.35);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f1f5f9;
        }
        thead th {
            padding: 14px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background .15s;
        }
        tbody tr:hover {
            background: #f8fafc;
        }
        tbody td {
            padding: 14px 20px;
            font-size: 14px;
            color: #334155;
        }
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #94a3b8;
        }
        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
            stroke: #cbd5e1;
        }
        .empty-state p {
            font-size: 15px;
        }
        .tag {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .tag-blue { background: #dbeafe; color: #1d4ed8; }
    </style>
</head>
<body>

<div class="container">
    <div class="page-header">
        <span class="badge">BNGRC</span>
        <h1>Liste des dons</h1>
        <p>Récapitulatif de tous les dons enregistrés</p>
    </div>

    <div class="card">
        <div class="card-toolbar">
            <span class="count"><strong><?= count($dons ?? []) ?></strong> don(s) enregistré(s)</span>
            <a href="<?= $base_url ?>/showFormDon" class="btn-add">+ Nouveau don</a>
        </div>

        <?php if (!empty($dons)): ?>
        <table>
            <thead>
              1  <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Date de saisie</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><?= htmlspecialchars($don['id_produit']) ?></td>
                        <td><span class="tag tag-blue"><?= htmlspecialchars($don['quantite']) ?></span></td>
                        <td><?= htmlspecialchars($don['date_don']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>
            <p>Aucun don enregistré pour le moment</p>
        </div>
        <?php endif; ?>
    </div>
</div>
  <?php
    Flight::render('partial/footer.php');
    ?>

</body>
</html>
