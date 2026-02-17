<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Simuler dispatch — BNGRC</title>
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
			padding: 24px;
		}

		.page {
			flex: 1;
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
		}

		.card {
			background: #fff;
			border-radius: 16px;
			box-shadow: 0 8px 32px rgba(0, 0, 0, 0.10);
			padding: 40px 36px 32px;
			width: 100%;
			max-width: 650px;
		}

		.card-header {
			text-align: center;
			margin-bottom: 22px;
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
			margin-bottom: 18px;
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
			box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.12);
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
			box-shadow: 0 4px 14px rgba(26, 86, 219, 0.35);
		}

		.btn-submit:active {
			transform: translateY(0);
		}

		.alert {
			border-radius: 12px;
			padding: 12px 14px;
			margin: 14px 0 18px;
			font-size: 13px;
			border: 1px solid;
		}

		.alert-success {
			background: #ecfdf5;
			border-color: #a7f3d0;
			color: #065f46;
		}

		.alert-error {
			background: #fef2f2;
			border-color: #fecaca;
			color: #7f1d1d;
		}

		.back-link {
			display: block;
			text-align: center;
			margin-top: 16px;
			font-size: 13px;
			color: #64748b;
			text-decoration: none;
		}

		.back-link:hover {
			color: #1a56db;
		}
	</style>
</head>

<body>

	<main class="page">
		<div class="card">
			<div class="card-header">
				<span class="badge">BNGRC</span>
				<h1>Simulation / Insertion de dispatch</h1>
				<p>Choisissez un besoin et attribuez une quantité</p>
			</div>

			<?php if (isset($message)): ?>
				<?php if (!empty($success)): ?>
					<div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
				<?php else: ?>
					<div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
				<?php endif; ?>
			<?php endif; ?>

			<div>
				<select name="id_produit" id="id_produit" required>
					<option value="">-- choisir un produit --</option>
					<?php
					foreach ($produit as $p): ?>
						<option value="<?php echo (int) $p['id_produit']; ?>">
							<?php echo htmlspecialchars($p['nom_produit']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<form method="post" action="<?= $base_url ?>/dispatch/create">
				<div class="form-group">
					<label for="id_besoin">Sélectionner un besoin</label>
					<select name="id_besoin" id="id_besoin" required>
						<option value="">-- choisir --</option>
						<?php if (!empty($besoin) && is_array($besoin)):
							foreach ($besoin as $b): ?>
								<option value="<?php echo (int) $b['id_besoin']; ?>">ID <?php echo (int) $b['id_besoin']; ?> -
									Ville
									<?php echo htmlspecialchars($b['id_ville']); ?> - Produit
									<?php echo htmlspecialchars($b['id_produit']); ?> - Qté demandée:
									<?php echo htmlspecialchars($b['quantite']); ?>
								</option>
							<?php endforeach; endif; ?>
					</select>
				</div>

				<div class="form-group">
					<label for="quantite">Quantité à attribuer</label>
					<input type="number" name="quantite" id="quantite" min="1" required>
				</div>

				<div>
					<button type="submit" class="btn-submit">⚡ Créer dispatch</button>
				</div>
			</form>

			<a class="back-link" href="<?= $base_url ?>/">← Retour au tableau de bord</a>
		</div>
	</main>

		<!-- Remaining total display -->
		<div style="width:100%;max-width:650px;margin:12px auto;text-align:center;">
			<div id="remaining-info" style="background:#fff;border-radius:10px;padding:12px;border:1px solid #e6e6e6;max-width:650px;margin:0 auto;color:#0f172a;">Besoin restant: —</div>
		</div>

	<?php Flight::render('partial/footer.php'); ?>
</body>

<script>
	var select = document.getElementById("id_produit");
	select.addEventListener("change", function() {
		var id_produit = this.value;
		var selectBesoin = document.getElementById("id_besoin");
		var remainingInfo = document.getElementById('remaining-info');

		if (!id_produit) {
			selectBesoin.innerHTML = '<option value="">-- choisir --</option>';
			remainingInfo.textContent = 'Besoin restant: —';
			return;
		}

		ajax({id_produit: id_produit}, "<?= $base_url ?>/dispatch/getInfoByProduit")
			.then(data => {
				// Afficher total restant
				remainingInfo.textContent = 'Total restant: ' + (data.totalRestant || 0);

				// Remplir le dropdown avec les vrais id_besoin
				selectBesoin.innerHTML = '<option value="">-- choisir --</option>';
				if (data.besoins && data.besoins.length > 0) {
					data.besoins.forEach(function(b) {
						var option = document.createElement("option");
						option.value = b.id_besoin;
						option.textContent = b.nom_ville + " — Besoin: " + b.quantite_besoin + " — Reste: " + b.reste;
						selectBesoin.appendChild(option);
					});
				} else {
					selectBesoin.innerHTML = '<option value="">Aucun besoin restant</option>';
				}
			})
			.catch(error => console.error("Erreur:", error));
	});

	function ajax(data, lien) {
		return fetch(lien, { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(data) })
			.then(res => { if (!res.ok) throw new Error("Erreur réseau"); return res.json(); });
	}
</script>

</html>