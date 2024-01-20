<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>

    <style>
        body {
            background-color: white;
            color: black;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .centered-box {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 350px;
        }

        h1 {
            color: red;
            text-align: center;
        }

        .button {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            width: 100%;
        }
        .button:hover {
            background-color: darkred;
        }

        input[type=email], input[type=password], input[type=text] {
            border: 2px solid red;
            padding: 5px;
            margin-bottom: 10px;
            width: calc(100% - 10px);
        }

        a {
            color: red;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
        }
        a:hover {
            text-decoration: underline;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }
    </style>

    <script>
            document.addEventListener('DOMContentLoaded', function() {
            var emailInput = document.getElementById('email');
            var form = document.querySelector('form');
            var emailError = document.createElement('div');
            emailError.style.color = 'red';
            emailError.style.display = 'none';
            emailInput.parentNode.insertBefore(emailError, emailInput.nextSibling);

            function checkEmail() {
            var email = emailInput.value;
            if (email) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'check-email/', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
            if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.exists) {
            emailError.textContent = 'Email již existuje!';
            emailError.style.display = 'block';
            emailInput.style.borderColor = 'red';
            emailInput.dataset.exists = 'true';
        } else {
            emailError.style.display = 'none';
            emailInput.style.borderColor = 'green';
            emailInput.dataset.exists = 'false';
        }
        }
        };
            xhr.send('email=' + encodeURIComponent(email));
        } else {
            emailError.style.display = 'none';
            emailInput.style.borderColor = 'green';
            emailInput.dataset.exists = 'false';
        }
        }

            emailInput.addEventListener('input', checkEmail);

            form.addEventListener('submit', function(event) {
            if (emailInput.dataset.exists === 'true') {
            event.preventDefault();
            alert('Prosím, zadejte jiný email.');
        }
        });
        });
    </script>


</head>
<body>

<div class="centered-box">
<h1>Registrace</h1>

<form method="post">
    <label for="name">Jméno: </label>
    <input type="text" name="name" id="name" required placeholder="Jméno"><br>

    <label for="surname">Příjmení: </label>
    <input type="text" name="surname" id="surname" required placeholder="Příjmení"><br>

    <label for="email">Email: </label>
    <input type="email" name="email" id="email" required placeholder="vasek@vlaky.cz"><br>

    <label for="password">Heslo: </label>
    <input type="password" name="password" id="password" required placeholder="Heslo"><br>

    <input type="submit" class="button" value="Registrovat">
</form>

<br>
<a href="login/">Pokud už máte účet můžete se přihlásit</a>

</div>
</body>

</html>
