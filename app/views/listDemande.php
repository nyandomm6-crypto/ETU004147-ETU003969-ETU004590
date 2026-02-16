<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <div id="base_url" style="display:none;"><?php echo isset($base_url) ? $base_url : ''; ?></div>
    <?php if (isset($demandes) && !empty($demandes)): ?>
        <h1>Mes demandes</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Objet demandé</th>
                    <th>Objet proposé</th>
                    <th>Demandeur</th>
                    <th>État</th>
                    <th>Date de création</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $d): ?>
                    <tr>
                        <td><?php echo $d['id']; ?></td>
                        <td><?php echo $d['demande']; ?></td>
                        <td><?php echo $d['echange']; ?></td>
                        <td><?php echo $d['demandeur']; ?></td>
                        <td><?php echo $d['etat']; ?></td>
                        <td><?php echo $d['date_demande']; ?></td>
                        <td>
                      <button onclick="accepterDemande(<?php echo $d['id']; ?>)">accepter</button>
                      <button onclick="refuserDemande(<?php echo $d['id']; ?>)">refuser</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune demande trouvée.</p>
    <?php endif; ?>
    <script>
function accepterDemande(id) {
    const baseUrl = document.getElementById('base_url').textContent;
    const data = { id: id };
    const lien = baseUrl + '/demande/accepter';
   ajax(data, lien)
        .then(response => {
            if (response.success) {
                alert("Demande acceptée");
                window.location.reload();
            } else {
                alert("Erreur lors de l'acceptation de la demande");
            }
        })
        .catch(error => {
            console.error("Erreur AJAX:", error);
            alert("Une erreur s'est produite lors de l'acceptation de la demande.");
        });
}

function refuserDemande(id) {
const baseUrl = document.getElementById('base_url').textContent;
    const data = { id: id };
    const lien = baseUrl + '/demande/refuser';
    ajax(data, lien)
        .then(response => {
            if (response.success) {
                alert("Demande refusée");
                window.location.reload();
            } else {
                alert("Erreur lors du refus de la demande");
            }
        })
        .catch(error => {
            console.error("Erreur AJAX:", error);
            alert("Une erreur s'est produite lors du refus de la demande.");
        });
}

function ajax(data, lien) {
        return fetch(lien, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
            .then(res => {
                if (!res.ok) throw new Error("Erreur réseau");
                return res.json();
            });
    }
    </script>
</body>
</html>