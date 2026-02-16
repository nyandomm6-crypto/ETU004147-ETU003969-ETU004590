<!-- protection donne -->
<?php
$q = isset($q) ? $q : '';
$cat = isset($cat) ? $cat : null;
$categories = isset($categories) ? $categories : [];
$objects = isset($objects) ? $objects : [];
$objets_en_demande = isset($objets_en_demande) ? $objets_en_demande : [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objets des autres</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Objets des autres utilisateurs</h1>
    <div id="base_url"><?php echo isset($base_url) ? $base_url : ''; ?></div>

    <p><a href="<?php echo isset($base_url) ? $base_url : ''; ?>/accueil">Retour à l'accueil</a></p>

    
    <form method="get" action="<?php echo $base_url; ?>/objet/search" style="margin-bottom: 15px;">
    
    <input type="text"
           name="q"
           placeholder="Mot-cle"
           value="<?php echo ($q); ?>">

    <select name="cat">
        <option value="">Toutes les catégories</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['id']; ?>"
                <?php if ($cat == $c['id']) echo 'selected'; ?>>
                <?php echo ($c['nom']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Rechercher</button>
</form>

    <?php if (empty($objects)): ?>
        <p>Aucun objet disponible pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>État</th>
                    <th>Catégorie</th>
                    <th>Propriétaire</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objects as $o): ?>
                    <tr>
                        <td><?php echo ($o['id']); ?></td>
                        <td><a href="<?php echo isset($base_url) ? $base_url : ''; ?>/objet/detail/<?php echo $o['id']; ?>"><?php echo ($o['nom']); ?></a></td>
                        <td><?php echo ($o['description'] ?? ''); ?></td>
                        <td><?php echo ($o['prix']); ?></td>
                        <td><?php echo ($o['etat']); ?></td>
                        <td><?php echo ($o['categorie_nom'] ?? ''); ?></td>
                        <td><?php echo ($o['user_nom'] ?? ''); ?></td>
                        <td>
                            <?php if (in_array((int)$o['id'], $objets_en_demande)): ?>
                                <!-- Demande déjà envoyée pour cet objet -->
                                <span style="color: orange; font-weight: bold;">Demande en cours</span>
                            <?php else: ?>
                                <button type="button" onclick="toggleMyObjetSelect(this)">Échanger</button>
                                <div class="selectMyObjetDiv" style="display: none; margin-top: 5px;">
                                    <select name="my_objet">
                                        <option value="">-- Choisir mon objet --</option>
                                        <?php if (isset($my_objects) && is_array($my_objects)): ?>
                                            <?php foreach ($my_objects as $mo): ?>
                                                <option value="<?php echo $mo['id']; ?>"><?php echo htmlspecialchars($mo['nom']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <button type="button" onclick="validateSelection(this, <?php echo $o['id']; ?>)">Valider</button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>
        // Affiche ou cache le select "mes objets"
        function toggleMyObjetSelect(btn) {
            var container = btn.parentElement.querySelector('.selectMyObjetDiv');
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }

        // Valider la sélection et envoyer la demande
        function validateSelection(btn, idObjetOther) {
            var container = btn.parentElement;
            var select = container.querySelector('select');
            if (select.value) {
                var data = {
                    id_myobjet: select.value,
                    id_objetEchange: idObjetOther
                };
                var lien = document.getElementById('base_url').textContent.trim() + '/createDemande';
                
                // Passer la cellule <td> pour la mettre à jour après succès
                var tdCell = btn.closest('td');
                ajax(data, lien, tdCell);
            } else {
                alert('Veuillez sélectionner un de vos objets.');
            }
        }

        function ajax(data, lien, tdCell) {
            fetch(lien, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
            .then(function(res) {
                if (!res.ok) throw new Error("Erreur réseau");
                return res.json();
            })
            .then(function(json) {
                if (json.success) {
                    // Mettre à jour la cellule : remplacer le bouton par "Demande en cours"
                    tdCell.innerHTML = '<span style="color: orange; font-weight: bold;">Demande en cours</span>';
                } else {
                    alert('Erreur: ' + (json.error || 'Échec de la demande'));
                }
            })
            .catch(function(err) {
                alert('Erreur: ' + err.message);
            });
        }
    </script>

</body>
</html>
