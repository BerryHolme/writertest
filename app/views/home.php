<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Writer</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"/>

    <style>

        .navbar {
            background-color: white;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        #navcontent a {
            display: inline-block;
            padding: 8px 15px;
            background-color: white;
            color: black;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        #navcontent a:hover {
            background-color: red;
            color: white;
        }

        .article {
            background-color: white;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .button{
            background-color: lightgrey;
            color: black;
            border: none;
        }
        .button:hover{
            background-color: red;
            color: white;
        }

        input[type=text]{
            border: 2px solid red;
        }

        .name{
            color: red;
        }

        .small{
            color: grey;
        }

        .image{
            border-radius: 8px;
            width: 300px;
        }
        .image:hover{
            border-radius: 8px;
            width: 300px;
            opacity: 0.7;
        }


    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'check-login/', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var contentDiv = document.getElementById('navcontent');

                    if (response.loggedIn) {
                        var links = "<h1>Vítejte na writer uživateli " + response.userName + ' ' +  response.userSurname + "</h1>";
                        links += "<a href='/writertest'>Domů</a> <a href='/writertest/account/'>Účet</a>";
                        if (response.userRole == 1 || response.userRole == 2) {
                            links += " <a href='/writertest/new-article/'>Nový článek</a> <a href='/writertest/newcategory/'>Nová kategorie</a>";
                        }
                        links += " <a href='/writertest/categories/'>Seznam kategorií</a> <a href='/writertest/logout/'>Odhlásit</a>";
                        contentDiv.innerHTML = links;
                    } else {
                        contentDiv.innerHTML = "<h1>Vítejte na writer</h1> <a href='/writertest'>Domů</a> <a href='/writertest/login/'>Přihlásit se</a> <a href='/writertest/register/'>Registrovat se</a>";
                    }
                }
            };
            xhr.send();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadArticles();
        });



        function loadArticles() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetchArticles', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('articlesList').innerHTML = this.responseText;
                }else{
                    document.getElementById('articlesList').innerHTML = 'Zatím žádný článek neexistuje';
                }
            };
            xhr.send();
        }

        function searchArticles() {
            var input = document.getElementById('searchInput').value;

            if (input.trim() === '') {
                loadArticles();
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'searchArticles?query=' + encodeURIComponent(input), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('articlesList').innerHTML = this.responseText;
                } else {
                    loadArticles();
                }
            };
            xhr.send();
        }


    </script>


</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div id="navcontent">

    </div>
</nav>

<br>

<input type="text" id="searchInput" onkeyup="searchArticles()" placeholder="Vyhledat články...">

<div id="articlesList">

</div>


</body>
</html>
