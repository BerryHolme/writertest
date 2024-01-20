<!-- newArticle.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Nový článek</title>

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

        input[type=text]{
            border: 2px solid red;
            width: 30%;
        }

        .textarea{
            border: 2px solid red;
            width: 30%;
            height: 20vh;
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


    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/writertest/check-login/', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var contentDiv = document.getElementById('navcontent');

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

    <script>
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetchCategories', true);
        xhr.onload = function() {
            if (this.status == 200) {
                document.getElementById('category').innerHTML = this.responseText;
            }
        };
        xhr.send();


    </script>

    <script>
        document.getElementById('yourFormId').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'postNewArticle', true);
            xhr.onload = function() {
                if (this.status == 200) {
                    window.location.href = '/';
                } else {
                    document.getElementById('errorMessage').textContent = this.responseText;
                }
            };
            xhr.send(formData);
        });
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div id="navcontent">


    </div>
    <div class="username">
        {{@SESSION.user.name}} {{@SESSION.user.surname}}
    </div>

</nav>
<h1>Nový článek</h1>

<form action="postNewArticle" method="post" enctype="multipart/form-data">
    <div>
        <label for="name">Titulek článku:</label><br>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="content">Obsah článku:</label><br>
        <textarea class="textarea" id="content" name="content" required></textarea>
    </div>
    <div>
        <label for="category">Kategorie: </label>
        <select id="category" name="category">
        </select>
    </div>

    <div>
        <label for="image">Obrázek článku (.jpg, max 25MB):</label>
        <input class="button" type="file" id="image" name="image" accept=".jpg">
    </div>

    <input type="submit" class="button" value="Vytvořit článek">
</form>


</body>
</html>
