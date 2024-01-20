<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"/>
    <title>Váš účet</title>

    <style>
        .navbar {
            background-color: white;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        #content a {
            display: inline-block;
            padding: 8px 15px;
            background-color: white;
            color: black;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        #content a:hover {
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
        .username{
            float: right;
            width: 300px;
            padding: 10px;
            color: black;
            text-align: right;
        }

        .username:hover{
            float: right;
            width: 300px;
            padding: 10px;
            color: red;
            text-align: right;

        }


    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form');
            var messageDiv = document.createElement('div');
            form.appendChild(messageDiv);

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                var oldPassword = document.getElementById('old').value;
                var newPassword = document.getElementById('new').value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'new-password/', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.state) {
                            messageDiv.textContent = 'Změna hesla proběhla úspěšně.';
                            messageDiv.style.color = 'green';
                        } else {
                            messageDiv.textContent = 'Staré heslo se neschoduje.';
                            messageDiv.style.color = 'red';
                        }
                    }
                };
                xhr.send('old=' + encodeURIComponent(oldPassword) + '&new=' + encodeURIComponent(newPassword));
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadUserArticles();
        });

        function loadUserArticles() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'my-articles', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('articlesList').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
        function confirmDelete(articleId) {
            if (confirm('Opravdu chcete smazat tento článek?')) {
                deleteArticle(articleId);
            }
        }


        function deleteArticle(articleId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'delete-article/' + articleId, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status === 200) {
                    loadUserArticles();
                }
            };
            xhr.send();
        }

        function editArticle(articleId) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'edit-article/' + articleId, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status === 200) {
                    loadUserArticles();
                }
            };
            xhr.send();
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'check-login/', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var contentDiv = document.getElementById('content');

                    if (response.loggedIn) {
                        var links = "";
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

</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div id="content">


    </div>
    <div class="username">
        {{@SESSION.user.name}} {{@SESSION.user.surname}}
    </div>

</nav>

<h1>{{@SESSION.user.name}} {{@SESSION.user.surname}}</h1> <br>

<b>Váš email:</b> {{@SESSION.user.email}} <br>
<b>Váše role:</b> {{@SESSION.user.rolename}}
<br><br>

<b>Vytvořit nové heslo:</b><br>

<form method="post" class="form">
    <label for="old">Vaše staré heslo: </label>
    <input type="text" name="old" id="old">

    <br>

    <label for="new">Vaše nové heslo: </label>
    <input type="text" name="new" id="new">

    <input type="submit" value="Změnit heslo" class="button">
</form>


<br>
<check if="{{@SESSION.user.role == 1}}"><true><a href="user-table/"><button class="button">Správa uživatelů</button></a></true></check>

<br><br>

<check if="{{@SESSION.user.role == 1}}"><true><h2>Vaše články</h2></true></check>
<check if="{{@SESSION.user.role == 2}}"><true><h2>Vaše články</h2></true></check>
<div id="articlesList">

</div>



</body>
</html>
