<?php
/**
 * Dispatch results view
 * Expects:
 * - $dispatches: array of dispatch rows (see dashboard)
 * - $remaining_besoins: optional array of remaining besoins
 * - $remaining_dons: optional array of remaining dons
 */
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Résultat du dispatch</title>
</head>
<body>
    <h1>Simulation de dispatch</h1>
    <p><a href="?page=dashboard">Retour au tableau de bord</a></p>

    <?php if (!empty($dispatches)): ?>
        <h2>Attributions</h2>
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th>Ville</th>
                    <th>Produit</th>
                    <th>Don (ID)</th>
                    <th>Besoin (ID)</th>
                    <th>Quantité attribuée</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dispatches as $d): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($d['nom_ville'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['nom_produit'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['id_don'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['id_besoin'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['quantite_attribuee'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['date_dispatch'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune attribution réalisée.</p>
    <?php endif; ?>

    <?php if (!empty($remaining_besoins)): ?>
        <h2>Besoins non satisfaits</h2>
        <ul>
            <?php foreach ($remaining_besoins as $b): ?>
                <li><?php echo htmlspecialchars(($b['nom_ville'] ?? '') . ' - ' . ($b['nom_produit'] ?? '') . ' : ' . ($b['quantite_restante'] ?? '')); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($remaining_dons)): ?>
        <h2>Dons non utilisés</h2>
        <ul>
            <?php foreach ($remaining_dons as $d): ?>
                <li><?php echo htmlspecialchars(($d['nom_ville'] ?? '') . ' - ' . ($d['nom_produit'] ?? '') . ' : ' . ($d['quantite_restante'] ?? '')); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>
