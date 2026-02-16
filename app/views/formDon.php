<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie de Don — BNGRC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f0fe 0%, #f5f7fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10);
            padding: 40px 36px 32px;
            width: 100%;
            max-width: 480px;
        }
        .card-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .card-header .badge {
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
        .card-header h1 {
            font-size: 22px;
            color: #1e293b;
            font-weight: 700;
        }
        .card-header p {
            color: #64748b;
            font-size: 13px;
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14px;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .form-group select:focus,
        .form-group input:focus {
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26,86,219,0.12);
            background: #fff;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1a56db, #2563eb);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform .15s, box-shadow .2s;
            margin-top: 6px;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(26,86,219,0.35);
        }
        .btn-submit:active { transform: translateY(0); }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">
            <span class="badge">BNGRC</span>
            <h1>Enregistrer un Don</h1>
            <p>Remplissez les champs ci-dessous</p>
        </div>

        <form action="<?= $base_url ?>/createDon" method="post">

            <div class="form-group">
                <label for="id_ville">Ville</label>
                <select name="id_ville" id="id_ville" required>
                    <option value="">-- choisir --</option>
                    <?php if (!empty($villes)):
                        foreach ($villes as $v): ?>
                            <option value="<?php echo (int) $v['id_ville']; ?>">
                                <?php echo htmlspecialchars($v['nom_ville']); ?>
                            </option>
                        <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_produit">Produit</label>
                <select name="id_produit" id="id_produit" required>
                    <option value="">-- choisir --</option>
                    <?php if (!empty($produits)):
                        foreach ($produits as $p): ?>
                            <option value="<?php echo (int) $p['id_produit']; ?>">
                                <?php echo htmlspecialchars($p['nom_produit']); ?>
                            </option>
                        <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantite">Quantité</label>
                <input type="number" name="quantite" id="quantite" min="1" required>
            </div>

            <div>
                <button type="submit" class="btn-submit">🎁 Enregistrer le don</button>
            </div>
        </form>

        <a href="<?= $base_url ?>/dashboard" class="back-link">← Retour au tableau de bord</a>
    </div>
</body>
</html>