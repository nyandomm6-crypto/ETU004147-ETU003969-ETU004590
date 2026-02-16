<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>

    <form onsubmit="return false;">
        <input type="text" id="nom" placeholder="Nom"><br><br>

        <input type="email" id="email" placeholder="Email"><br><br>

        <input type="password" id="password" placeholder="Mot de passe"><br><br>

        <input type="password" id="confirmPassword" placeholder="Confirmer mot de passe"><br><br>

        <button type="button" id="signupBtn" disabled onclick="signUp()">Sign Up</button>
    </form>

    <div id="errorMessagesNom"></div>
    <div id="errorMessagesEmail"></div>
    <div id="errorMessagesPassword"></div>

</body>

<script>
    const nom = document.getElementById("nom");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const signupBtn = document.getElementById("signupBtn");

    let isNomValid = false;
    let isEmailValid = false;
    let isPasswordValid = false;

    function checkFormValidity() {
        signupBtn.disabled = !(isNomValid && isEmailValid && isPasswordValid);
    }

    // ===== NOM =====
    nom.addEventListener("input", () => {
        ajax({ nom: nom.value }, "/web/takalo/validateName")
            .then(data => {
                const div = document.getElementById("errorMessagesNom");
                div.innerHTML = data.message.map(m => `<p>${m}</p>`).join("");

                isNomValid = data.message.length === 0;
                checkFormValidity();
            });
    });

    // ===== EMAIL =====
    email.addEventListener("input", () => {
        ajax({ email: email.value }, "/web/takalo/validateEmail")
            .then(data => {
                const div = document.getElementById("errorMessagesEmail");
                div.innerHTML = data.message.map(m => `<p>${m}</p>`).join("");

                isEmailValid = data.message.length === 0;
                checkFormValidity();
            });
    });

    // ===== PASSWORD =====
    function validatePasswordAjax() {
        ajax(
            { password: password.value, confirmPassword: confirmPassword.value },
            "/web/takalo/validatePassword"
        )
            .then(data => {
                const div = document.getElementById("errorMessagesPassword");
                div.innerHTML = data.message.map(m => `<p>${m}</p>`).join("");

                isPasswordValid = data.message.length === 0;
                checkFormValidity();
            });
    }

    password.addEventListener("input", validatePasswordAjax);
    confirmPassword.addEventListener("input", validatePasswordAjax);

    // ===== AJAX FUNCTION =====
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

    // ===== SIGN UP =====
    function signUp() {
        if (!(isNomValid && isEmailValid && isPasswordValid)) return;

        const data = {
            nom: nom.value,
            email: email.value,
            password: password.value,
            confirmPassword: confirmPassword.value
        };

        ajax(data, "/web/takalo/signup")
            .then(res => {
                if (res.success) {
                        window.location.href = "/web/takalo/login";
                        return;
                } else {
                    alert("Erreur lors de l'inscription");
                }
            })
            .catch(err => console.error(err));
    }
</script>

</html>