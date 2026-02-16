<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Simuler dispatch</title>
</head>

<body>
	<h1>Simulation / Insertion de dispatch</h1>
	<p><a href="<?= $base_url ?>dashboard">Retour au tableau de bord</a></p>

	<?php if (isset($message)): ?>
		<div>
			<?php if (!empty($success)): ?>
				<p style="color:green"><?php echo htmlspecialchars($message); ?></p>
			<?php else: ?>
				<p style="color:red"><?php echo htmlspecialchars($message); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<form method="post" action="<?= $base_url ?>dispatch/create">
		<div>
			<label for="id_besoin">Sélectionner un besoin</label>
			<select name="id_besoin" id="id_besoin" required>
				<option value="">-- choisir --</option>
				<?php if (!empty($besoin) && is_array($besoin)):
					foreach ($besoin as $b): ?>
						<option value="<?php echo (int) $b['id_besoin']; ?>">ID <?php echo (int) $b['id_besoin']; ?> - Ville
							<?php echo htmlspecialchars($b['id_ville']); ?> - Produit
							<?php echo htmlspecialchars($b['id_produit']); ?> - Qté demandée:
							<?php echo htmlspecialchars($b['quantite']); ?></option>
					<?php endforeach; endif; ?>
			</select>
		</div>

		<div>
			<label for="quantite">Quantité à attribuer</label>
			<input type="number" name="quantite" id="quantite" min="1" required>
		</div>

		<div>
			<button type="submit">Créer dispatch</button>
		</div>
	</form>


</body>

</html>