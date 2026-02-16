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
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tableau de bord — BNGRC</title>
	<style>
		* { margin: 0; padding: 0; box-sizing: border-box; }
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background: #f0f4f8;
			min-height: 100vh;
			color: #1e293b;
		}
		/* ── Top bar ─────────────────────────── */
		.topbar {
			background: linear-gradient(135deg, #0f3460, #1a56db);
			color: #fff;
			padding: 18px 32px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			box-shadow: 0 2px 12px rgba(0,0,0,0.15);
		}
		.topbar .brand {
			display: flex;
			align-items: center;
			gap: 12px;
		}
		.topbar .brand .logo {
			background: #fff;
			color: #1a56db;
			font-weight: 800;
			font-size: 13px;
			padding: 6px 12px;
			border-radius: 8px;
			letter-spacing: 1px;
		}
		.topbar .brand h1 {
			font-size: 18px;
			font-weight: 600;
		}
		nav { display: flex; gap: 8px; }
		nav a {
			color: #fff;
			text-decoration: none;
			padding: 8px 16px;
			border-radius: 8px;
			font-size: 13px;
			font-weight: 600;
			background: rgba(255,255,255,0.12);
			transition: background .2s;
		}
		nav a:hover { background: rgba(255,255,255,0.25); }
		/* ── Container ───────────────────────── */
		.container { max-width: 1100px; margin: 0 auto; padding: 32px 20px; }
		/* ── Section titles ───────────────────── */
		.section-title {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 20px;
			margin-top: 36px;
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
		/* ── Cards ────────────────────────────── */
		.card {
			background: #fff;
			border-radius: 14px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.06);
			overflow: hidden;
			margin-bottom: 24px;
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
		}
		.dot-blue  { background: #3b82f6; }
		.dot-green { background: #22c55e; }
		.dot-amber { background: #f59e0b; }
		/* ── Tables ───────────────────────────── */
		table { width: 100%; border-collapse: collapse; }
		thead { background: #f1f5f9; }
		thead th {
			padding: 12px 18px;
			text-align: left;
			font-size: 11px;
			font-weight: 700;
			color: #64748b;
			text-transform: uppercase;
			letter-spacing: .5px;
		}
		tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .12s; }
		tbody tr:hover { background: #f8fafc; }
		tbody td { padding: 12px 18px; font-size: 14px; color: #334155; }
		.tag {
			display: inline-block;
			padding: 2px 10px;
			border-radius: 6px;
			font-size: 12px;
			font-weight: 600;
		}
		.tag-blue  { background: #dbeafe; color: #1d4ed8; }
		.tag-green { background: #dcfce7; color: #15803d; }
		.empty { text-align: center; padding: 32px; color: #94a3b8; font-size: 14px; }
	</style>
</head>
<body>

	<!-- ── Top bar ─────────────────────────── -->
	<div class="topbar">
		<div class="brand">
			<span class="logo">BNGRC</span>
			<h1>Tableau de bord — Suivi des dons</h1>
		</div>
		<nav>
			<a href="<?php echo $base_url; ?>/formBesoin">📋 Saisir un besoin</a>
			<a href="<?php echo $base_url; ?>/showFormDon">🎁 Saisir un don</a>
			<a href="<?php echo $base_url; ?>/showFormDispatch">⚡ Dispatcher</a>
		</nav>
	</div>

		<div class="section-title"><h2>Dons</h2><span class="line"></span></div>
		<div class="card">
	<div class="container">

		<!-- ── Villes & besoins ────────────────── -->
		<div class="section-title"><h2>Villes et besoins</h2><span class="line"></span></div>

		<?php if (!empty($villes) && is_array($villes)): ?>
			<?php foreach ($villes as $ville): ?>
				<div class="card">
					<div class="card-head">
						<span class="dot dot-blue"></span>
						<?php echo htmlspecialchars($ville['nom_ville'] ?? 'Ville inconnue'); ?>
					</div>

					<?php if (!empty($ville['besoins'])): ?>
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
										<td><?php echo htmlspecialchars($besoin['nom_produit'] ?? ''); ?></td>
										<td><?php echo htmlspecialchars($besoin['prix_unitaire'] ?? ''); ?></td>
										<td><span class="tag tag-blue"><?php echo htmlspecialchars($besoin['quantite'] ?? ''); ?></span></td>
										<td><?php $total = (float)($besoin['prix_unitaire'] ?? 0) * (int)($besoin['quantite'] ?? 0); echo number_format($total,2); ?></td>
										<td><?php echo htmlspecialchars($besoin['date_saisie'] ?? ''); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<p class="empty">Aucun besoin saisi pour cette ville.</p>
					<?php endif; ?>

					<?php if (!empty($ville['dons'])): ?>
						<div class="card-head" style="border-top:1px solid #e2e8f0;">
							<span class="dot dot-green"></span> Dons reçus
						</div>
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
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="card"><p class="empty">Aucune ville disponible. Veuillez saisir des villes et besoins.</p></div>
		<?php endif; ?>

		<?php if (!empty($dispatch_summary) && is_array($dispatch_summary)): ?>
		<section>
			<h2>Résumé des dispatchs par ville</h2>
			<table border="1" cellpadding="6" cellspacing="0">
				<thead>
					<tr>
						<th>Ville</th>
						<th>Total besoins</th>
						<th>Total attribué</th>
						<th>Besoins restants</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($dispatch_summary as $s): ?>
						<tr>
							<td><?php echo htmlspecialchars($s['nom_ville'] ?? ''); ?></td>
							<td><?php echo htmlspecialchars($s['total_besoin'] ?? 0); ?></td>
							<td><?php echo htmlspecialchars($s['total_attribue'] ?? 0); ?></td>
							<td><?php echo htmlspecialchars($s['besoin_restant'] ?? 0); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
		<?php endif; ?>

		<?php if (!empty($dispatches) && is_array($dispatches)): ?>
		<!-- ── Dons (liste brute) ─────────────── -->
		<div class="section-title"><h2>Dons</h2><span class="line"></span></div>
		<div class="card">
		<?php if (!empty($dons) && is_array($dons)): ?>
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
		<?php else: ?>
			<p class="empty">Aucun don enregistré.</p>
		<?php endif; ?>
		</div>

		<!-- ── Dispatch ───────────────────────── -->
		<?php if (!empty($dispatches) && is_array($dispatches)): ?>
		<div class="section-title"><h2>Résultats du dispatch</h2><span class="line"></span></div>
		<div class="card">
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
							<td><?php echo htmlspecialchars($d['id_don'] ?? ''); ?></td>
							<td><?php echo htmlspecialchars($d['id_besoin'] ?? ''); ?></td>
							<td><span class="tag tag-blue"><?php echo htmlspecialchars($d['quantite_attribuee'] ?? ''); ?></span></td>
							<td><?php echo htmlspecialchars($d['date_dispatch'] ?? ''); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
        <?php endif; ?>

	</div>
    <?php
    Flight::render('partial/footer.php');
    ?>

</body>
</html>