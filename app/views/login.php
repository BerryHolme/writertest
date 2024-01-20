<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>

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

        input[type=email], input[type=password] {
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
                                emailError.style.display = 'none';
                                emailInput.style.borderColor = 'green';
                                emailInput.dataset.exists = 'true';
                            } else {
                                emailError.textContent = 'Email neexistuje!';
                                emailError.style.display = 'block';
                                emailInput.style.borderColor = 'red';
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


        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var emailInput = document.getElementById('email');
            var passwordInput = document.getElementById('password');
            var form = document.querySelector('form');
            var errorDiv = document.createElement('div');
            errorDiv.style.color = 'red';
            form.appendChild(errorDiv);

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                var email = emailInput.value;
                var password = passwordInput.value;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'check-password/', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            form.submit();
                        } else {
                            errorDiv.textContent = 'Nesprávné heslo nebo e-mail';
                        }
                    }
                };
                xhr.send('email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password));
            });
        });
    </script>

</head>
<body>

<div class="centered-box">
<h1>Přihlášení</h1>

<form method="post">
    <label for="email">Email: </label>
    <input type="email" name="email" id="email" required placeholder="vasek@vlaky.cz"><br>

    <label for="password">Heslo: </label>
    <input type="password" name="password" id="password" required placeholder="Heslo"><br>

    <input type="submit" class="button" value="Přihlásit se">
</form>

<br>
<a href="register/">Pokud nemáte účet můžete se zaregistrovat</a>
    </div>
</body>
</html>
