<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dispatcher un don — BNGRC</title>
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
		}

		.page {
			flex: 1;
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			padding: 20px;
		}

		.card {
			background: #fff;
			border-radius: 16px;
			box-shadow: 0 8px 32px rgba(0, 0, 0, 0.10);
			padding: 40px 36px 32px;
			width: 100%;
			max-width: 700px;
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
		.form-group input[type="number"] {
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

		/* Radio buttons style */
		.radio-group {
			background: #f8fafc;
			border-radius: 12px;
			padding: 16px;
			margin-bottom: 18px;
			border: 1px solid #e2e8f0;
		}

		.radio-group-title {
			font-size: 13px;
			font-weight: 600;
			color: #334155;
			margin-bottom: 12px;
		}

		.radio-option {
			display: flex;
			align-items: flex-start;
			padding: 10px 12px;
			margin-bottom: 8px;
			border-radius: 8px;
			cursor: pointer;
			transition: background 0.2s;
		}

		.radio-option:hover {
			background: #e8f0fe;
		}

		.radio-option:last-child {
			margin-bottom: 0;
		}

		.radio-option input[type="radio"] {
			margin-right: 12px;
			margin-top: 3px;
			accent-color: #1a56db;
			width: 18px;
			height: 18px;
		}

		.radio-label {
			flex: 1;
		}

		.radio-label strong {
			display: block;
			font-size: 14px;
			color: #1e293b;
			margin-bottom: 2px;
		}

		.radio-label small {
			font-size: 12px;
			color: #64748b;
		}

		.btn-submit {
			width: 100%;
			padding: 14px;
			background: linear-gradient(135deg, #1a56db, #2563eb);
			color: #fff;
			border: none;
			border-radius: 10px;
			font-size: 15px;
			font-weight: 600;
			cursor: pointer;
			transition: transform .15s, box-shadow .2s;
			margin-top: 10px;
		}

		.btn-submit:hover {
			transform: translateY(-1px);
			box-shadow: 0 4px 14px rgba(26, 86, 219, 0.35);
		}

		.btn-submit:active {
			transform: translateY(0);
		}

		/* Info boxes */
		.info-box {
			background: #fff;
			border-radius: 10px;
			padding: 14px 16px;
			border: 1px solid #e6e6e6;
			margin-bottom: 18px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.info-box .label {
			font-size: 13px;
			color: #64748b;
		}

		.info-box .value {
			font-size: 18px;
			font-weight: 700;
			color: #1a56db;
		}

		.info-box.success .value {
			color: #22c55e;
		}

		.info-box.warning .value {
			color: #f59e0b;
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

		/* Hidden by default */
		.hidden {
			display: none;
		}
	</style>
</head>

<body>
	<?php Flight::render('partial/header.php'); ?>
<<<<<<< HEAD
=======

>>>>>>> 022281912f6519df808966c8df39eaf9fbf95645

	<main class="page">
		<div class="card">
			<div class="card-header">
				<span class="badge">BNGRC</span>
				<h1>Distribution automatique de don</h1>
				<p>Choisissez un produit, un mode de distribution et la quantité à dispatcher</p>
			</div>

			<?php if (isset($message)): ?>
				<?php if (!empty($success)): ?>
					<div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
				<?php else: ?>
					<div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
				<?php endif; ?>
			<?php endif; ?>

<<<<<<< HEAD
			<form method="post" action="<?= $base_url ?>/dispatch/create" id="dispatch-form">
				
				<!-- Sélection du produit -->
=======
			<div>
				<select name="id_produit" id="id_produit" required>
					<option value="">-- choisir un produit --</option>
					<?php
					foreach ($produit as $p): ?>
						<option value="<?php echo (int) $p['id_produit']; ?>">
							<?php echo htmlspecialchars($p['nom_produit']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<form method="post" action="<?= $base_url ?>/dispatch/create">
>>>>>>> 022281912f6519df808966c8df39eaf9fbf95645
				<div class="form-group">
					<label for="id_produit">1. Sélectionner un produit</label>
					<select name="id_produit" id="id_produit" required>
						<option value="">-- Choisir un produit --</option>
						<?php foreach ($produit as $p): ?>
							<option value="<?php echo (int) $p['id_produit']; ?>">
								<?php echo htmlspecialchars($p['nom_produit']); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<!-- Info don restant (affiché via AJAX) -->
				<div class="info-box success" id="don-info" style="display: none;">
					<span class="label">🎁 Don disponible pour ce produit</span>
					<span class="value" id="don-restant-value">0</span>
				</div>
				
				<input type="radio" name="categorie" value="vivres"> Par date de saisie <br>
				<input type="radio" name="categorie" value="materiaux"> Par demande le plus petit <br>
				<input type="radio" name="categorie" value="financier"> Par proportionnalite <br>




				<!-- Info besoin total restant -->
				<div class="info-box warning" id="besoin-info" style="display: none;">
					<span class="label">📦 Besoin total restant</span>
					<span class="value" id="besoin-restant-value">0</span>
				</div>

				<!-- Mode de distribution -->
				<div class="radio-group">
					<div class="radio-group-title">2. Mode de distribution</div>
					
					<label class="radio-option">
						<input type="radio" name="mode_distribution" value="par_date" checked>
						<div class="radio-label">
							<strong>📅 Par date de saisie</strong>
							<small>Priorise les besoins les plus anciens (premier arrivé, premier servi)</small>
						</div>
					</label>

					<label class="radio-option">
						<input type="radio" name="mode_distribution" value="par_petit">
						<div class="radio-label">
							<strong>📉 Par demande la plus petite</strong>
							<small>Priorise les besoins avec la plus petite quantité restante</small>
						</div>
					</label>

					<label class="radio-option">
						<input type="radio" name="mode_distribution" value="par_proportion">
						<div class="radio-label">
							<strong>⚖️ Par proportionnalité</strong>
							<small>Distribue proportionnellement selon le besoin de chaque ville</small>
						</div>
					</label>

					<label class="radio-option">
						<input type="radio" name="mode_distribution" value="manuel">
						<div class="radio-label">
							<strong>✋ Manuel</strong>
							<small>Choisir manuellement un besoin spécifique</small>
						</div>
					</label>
				</div>

				<!-- Sélection manuelle du besoin (affiché uniquement en mode manuel) -->
				<div class="form-group hidden" id="besoin-container">
					<label for="id_besoin">Sélectionner un besoin spécifique</label>
					<select name="id_besoin" id="id_besoin">
						<option value="">-- Choisir un besoin --</option>
					</select>
				</div>

				<!-- Quantité -->
				<div class="form-group">
					<label for="quantite">3. Quantité à distribuer</label>
					<input type="number" name="quantite" id="quantite" min="1" placeholder="Entrez la quantité" required>
				</div>

				<button type="submit" class="btn-submit">⚡ Distribuer le don</button>
			</form>

			<a class="back-link" href="<?= $base_url ?>/">← Retour au tableau de bord</a>
		</div>
	</main>

<<<<<<< HEAD
=======
	<!-- Remaining total display -->
	<div style="width:100%;max-width:650px;margin:12px auto;text-align:center;">
		<div id="remaining-info"
			style="background:#fff;border-radius:10px;padding:12px;border:1px solid #e6e6e6;max-width:650px;margin:0 auto;color:#0f172a;">
			Besoin restant: —</div>
	</div>

>>>>>>> 022281912f6519df808966c8df39eaf9fbf95645
	<?php Flight::render('partial/footer.php'); ?>

	<script>
		const produitSelect = document.getElementById("id_produit");
		const besoinSelect = document.getElementById("id_besoin");
		const besoinContainer = document.getElementById("besoin-container");
		const donInfo = document.getElementById("don-info");
		const besoinInfo = document.getElementById("besoin-info");
		const donRestantValue = document.getElementById("don-restant-value");
		const besoinRestantValue = document.getElementById("besoin-restant-value");
		const modeRadios = document.querySelectorAll('input[name="mode_distribution"]');
		const quantiteInput = document.getElementById("quantite");

		// Chargement des infos produit
		produitSelect.addEventListener("change", function() {
			const id_produit = this.value;

			if (!id_produit) {
				donInfo.style.display = "none";
				besoinInfo.style.display = "none";
				besoinSelect.innerHTML = '<option value="">-- Choisir un besoin --</option>';
				return;
			}

			ajax({ id_produit: id_produit }, "<?= $base_url ?>/dispatch/getInfoByProduit")
				.then(data => {
					// Afficher don restant
					donRestantValue.textContent = data.donRestant || 0;
					donInfo.style.display = "flex";

					// Afficher besoin total restant
					besoinRestantValue.textContent = data.totalRestant || 0;
					besoinInfo.style.display = "flex";

					// Remplir le dropdown des besoins
					besoinSelect.innerHTML = '<option value="">-- Choisir un besoin --</option>';
					if (data.besoins && data.besoins.length > 0) {
						data.besoins.forEach(function(b) {
							const option = document.createElement("option");
							option.value = b.id_besoin;
							option.textContent = b.nom_ville + " — Besoin: " + b.quantite_besoin + " — Reste: " + b.reste;
							besoinSelect.appendChild(option);
						});
					} else {
						besoinSelect.innerHTML = '<option value="">Aucun besoin restant</option>';
					}

					// Suggérer la quantité maximale
					const maxQte = Math.min(data.donRestant || 0, data.totalRestant || 0);
					quantiteInput.max = maxQte;
					quantiteInput.placeholder = "Max: " + maxQte;
				})
				.catch(error => console.error("Erreur:", error));
		});

		// Afficher/masquer le sélecteur de besoin selon le mode
		modeRadios.forEach(radio => {
			radio.addEventListener("change", function() {
				if (this.value === "manuel") {
					besoinContainer.classList.remove("hidden");
					besoinSelect.required = true;
				} else {
					besoinContainer.classList.add("hidden");
					besoinSelect.required = false;
				}
			});
		});

		function ajax(data, lien) {
			return fetch(lien, {
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify(data)
			}).then(res => {
				if (!res.ok) throw new Error("Erreur réseau");
				return res.json();
			});
		}
	</script>
</body>

<<<<<<< HEAD
=======
<script>
	var select = document.getElementById("id_produit");
	select.addEventListener("change", function () {
		var id_produit = this.value;
		var selectBesoin = document.getElementById("id_besoin");
		var remainingInfo = document.getElementById('remaining-info');

		if (!id_produit) {
			selectBesoin.innerHTML = '<option value="">-- choisir --</option>';
			remainingInfo.textContent = 'Besoin restant: —';
			return;
		}

		ajax({ id_produit: id_produit }, "<?= $base_url ?>/dispatch/getInfoByProduit")
			.then(data => {
				// Afficher total restant
				remainingInfo.textContent = 'Total restant: ' + (data.totalRestant || 0);

				// Remplir le dropdown avec les vrais id_besoin
				selectBesoin.innerHTML = '<option value="">-- choisir --</option>';
				if (data.besoins && data.besoins.length > 0) {
					data.besoins.forEach(function (b) {
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

>>>>>>> 022281912f6519df808966c8df39eaf9fbf95645
</html>