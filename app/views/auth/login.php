<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>

    <form onsubmit="return false;">
        <input type="email" id="email" placeholder="Email"><br><br>

        <input type="password" id="password" placeholder="Mot de passe"><br><br>

        <button type="button" id="loginBtn" disabled>Se connecter</button>
    </form>

    <div id="errorMessages"></div>

</body>

<script>
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const loginBtn = document.getElementById("loginBtn");

    function checkFormValidity() {
        loginBtn.disabled = !(email.value.trim() && password.value.trim());
    }

    email.addEventListener("input", checkFormValidity);
    password.addEventListener("input", checkFormValidity);

    loginBtn.addEventListener("click", () => {
        const data = {
            email: email.value,
            password: password.value
        };

        ajax(data, "/web/takalo/login")
            .then(res => {
                const div = document.getElementById("errorMessages");
                if (res.success) {
                    if (res.estAdmin) {
                        window.location.href = "/web/takalo/accueilAdmin";
                        return;
                    }
                    window.location.href = "/web/takalo/accueil";
                    return;
                } else {
                    alert("Connexion échouée");
                }

                const errors = res.errors || ["Erreur lors de la connexion."];
                div.innerHTML = errors.map(e => `<p>${e}</p>`).join("");
            })
            .catch(err => {
                console.error(err);
                document.getElementById("errorMessages").innerHTML = `<p>Erreur réseau</p>`;
            });
    });

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

</html>