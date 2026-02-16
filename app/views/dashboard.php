<?php
/**
 * Dashboard view
 * Expects (if provided):
 * - $villes: array of villes. Each ville may contain 'besoins' => [] and 'dons' => []
 * - $dons: list of all dons
 * - $dispatches: list of dispatch results
 */
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Tableau de bord - Suivi des dons</title>
</head>
<body>
	<h1>Tableau de bord</h1>

	<nav>
		<a href="<?php echo $base_url; ?>/besoin_form">Saisir un besoin</a> |
		<a href="<?php echo $base_url; ?>/don_form">Saisir un don</a> |
		<a href="<?php echo $base_url; ?>/dispatch">Simuler dispatch</a>
	</nav>

	<section>
		<h2>Villes et besoins</h2>
		<?php if (!empty($villes) && is_array($villes)): ?>
			<?php foreach ($villes as $ville): ?>
				<article>
					<h3><?php echo htmlspecialchars($ville['nom_ville'] ?? 'Ville inconnue'); ?></h3>

					<?php if (!empty($ville['besoins'])): ?>
						<table border="1" cellpadding="4" cellspacing="0">
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
										<td><?php echo htmlspecialchars($besoin['nom_produit'] ?? ''); ?></td>
										<td><?php echo htmlspecialchars($besoin['prix_unitaire'] ?? ''); ?></td>
										<td><?php echo htmlspecialchars($besoin['quantite'] ?? ''); ?></td>
										<td><?php $total = (float)($besoin['prix_unitaire'] ?? 0) * (int)($besoin['quantite'] ?? 0); echo number_format($total,2); ?></td>
										<td><?php echo htmlspecialchars($besoin['date_saisie'] ?? ''); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<p>Aucun besoin saisi pour cette ville.</p>
					<?php endif; ?>

					<?php if (!empty($ville['dons'])): ?>
						<h4>Dons attribués / reçus</h4>
						<table border="1" cellpadding="4" cellspacing="0">
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
										<td><?php echo htmlspecialchars($don['quantite'] ?? ''); ?></td>
										<td><?php echo htmlspecialchars($don['date_don'] ?? ''); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		<?php else: ?>
			<p>Aucune ville disponible. Veuillez saisir des villes et besoins.</p>
		<?php endif; ?>
	</section>

	<section>
		<h2>Dons (liste brute)</h2>
		<?php if (!empty($dons) && is_array($dons)): ?>
			<table border="1" cellpadding="4" cellspacing="0">
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
							<td><?php echo htmlspecialchars($don['quantite'] ?? ''); ?></td>
							<td><?php echo htmlspecialchars($don['date_don'] ?? ''); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p>Aucun don enregistré.</p>
		<?php endif; ?>
	</section>

	<?php if (!empty($dispatches) && is_array($dispatches)): ?>
		<section>
			<h2>Résultats du dispatch</h2>
			<table border="1" cellpadding="4" cellspacing="0">
				<thead>
					<tr>
						<th>Ville</th>
						<th>Produit</th>
						<th>Don (ID)</th>
						<th>Besoin (ID)</th>
						<th>Quantité attribuée</th>
						<th>Date dispatch</th>
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
		</section>
	<?php endif; ?>

</body>
</html>