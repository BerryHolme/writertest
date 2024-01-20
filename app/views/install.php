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
        h2{
            color: red;
            text-align: center;
            font-size: 20px;
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


</head>
<body>

<div class="centered-box">
    <h1>Install</h1>
    <h2>POZOR!<br>Toto smaže celou databázi!</h2>

    <form method="post" action="/writertest/postinstall/">

        <label for="password">Heslo: </label>
        <input type="password" name="password" id="password" required placeholder="Heslo"><br>

        <input type="submit" class="button" value="Nainstalovat">
    </form>

    <br>
</div>
</body>
</html>
