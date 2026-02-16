<?php
/**
 * Dispatch form view (no CSS)
 * Expects (optional):
 * - $dispatches: array of created dispatch rows
 * - $remaining_besoins: array of remaining besoins after simulation
 * - $remaining_dons: array of remaining dons after simulation
 */
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Simuler dispatch</title>
</head>
<body>
	<h1>Simulation / Insertion de dispatch</h1>
	<p><a href="?page=dashboard">Retour au tableau de bord</a></p>

	<form method="post" action="?action=simulateDispatch">
		<div>
			<label for="id_besoin">Sélectionner un besoin</label>
			<select name="id_besoin" id="id_besoin" required>
				<option value="">-- choisir --</option>
				<?php if (!empty($besoin) && is_array($besoin)): foreach ($besoin as $b): ?>
					<option value="<?php echo (int)$b['id_besoin']; ?>">ID <?php echo (int)$b['id_besoin']; ?> - Ville <?php echo htmlspecialchars($b['id_ville']); ?> - Produit <?php echo htmlspecialchars($b['id_produit']); ?> - Qté demandée: <?php echo htmlspecialchars($b['quantite']); ?></option>
				<?php endforeach; endif; ?>
			</select>
		</div>

		<div>
			<label for="quantite_attribuee">Quantité à attribuer</label>
			<input type="number" name="quantite_attribuee" id="quantite_attribuee" min="1" required>
		</div>

		<div>
			<button type="submit">Créer dispatch</button>
		</div>
	</form>

	<?php if (!empty($dispatches) && is_array($dispatches)): ?>
		<h2>Attributions créées</h2>
		<table border="1" cellpadding="4" cellspacing="0">
			<thead>
				<tr>
					<th>ID Don</th>
					<th>ID Besoin</th>
					<th>Quantité attribuée</th>
					<th>Date dispatch</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($dispatches as $d): ?>
					<tr>
						<td><?php echo htmlspecialchars($d['id_don'] ?? ''); ?></td>
						<td><?php echo htmlspecialchars($d['id_besoin'] ?? ''); ?></td>
						<td><?php echo htmlspecialchars($d['quantite_attribuee'] ?? ''); ?></td>
						<td><?php echo htmlspecialchars($d['date_dispatch'] ?? ''); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

	<?php if (!empty($remaining_besoins)): ?>
		<h3>Besoins non satisfaits</h3>
		<ul>
			<?php foreach ($remaining_besoins as $b): ?>
				<li><?php echo htmlspecialchars(($b['nom_ville'] ?? '') . ' - ' . ($b['nom_produit'] ?? '') . ' : ' . ($b['quantite_restante'] ?? '')); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if (!empty($remaining_dons)): ?>
		<h3>Dons non utilisés</h3>
		<ul>
			<?php foreach ($remaining_dons as $d): ?>
				<li><?php echo htmlspecialchars(($d['nom_ville'] ?? '') . ' - ' . ($d['nom_produit'] ?? '') . ' : ' . ($d['quantite_restante'] ?? '')); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

</body>
</html>
