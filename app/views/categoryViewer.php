<!DOCTYPE html>
<html>
<head>
    <title>Kategorie</title>

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

        .name{
            color: red;
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
        function searchCategories() {
            var input = document.getElementById('searchInput').value;
            var error = document.getElementById('error');
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'searchCategories?query=' + encodeURIComponent(input), true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('categoriesList').innerHTML = this.responseText;
                    error.textContent = "";
                } else {
                    error.style.color = "red";
                    error.textContent = "Nic nenalezeno";
                }
            };
            xhr.send();
        }


        function loadArticlesByCategory(categoryId) {
            if (!categoryId) return;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetchArticlesByCategories/' + categoryId, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('articlesList').innerHTML = this.responseText;
                }else{
                    document.getElementById('articlesList').innerHTML = "V této kategorii eště není vytvořen žádný článek";
                }
            };
            xhr.send();
        }

        function onlyOneCheckbox(checkbox) {
            var checkboxes = document.getElementsByName('categoryCheckbox');
            var isAnyChecked = false;
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false;
                if (item.checked) isAnyChecked = true;
            });

            if (isAnyChecked) {
                loadArticlesByCategory(checkbox.value);
            } else {
                document.getElementById('articlesList').innerHTML = '';
            }
        }


        window.onload = function() {
            searchCategories();
        };
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

<h1>Kategorie</h1>

<input type="text" id="searchInput" onkeyup="searchCategories()" placeholder="Vyhledat kategorie...">

<div id="categoriesList">

</div>


<div id="articlesList">

</div>

<div id="error"></div>


</body>
</html>
