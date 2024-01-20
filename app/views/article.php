<!DOCTYPE html>
<html>
<head>
    <title>Článek</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"/>

    <style>

        .navbar {
            background-color: white;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        /* Content link button styles */
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

        .textarea{
            border: 2px solid red;
            width: 30%; /* Šířka v procentech */
            height: 10vh; /* Výška v jednotkách viewport height */
        }

        .image{
            border-radius: 8px;
            width: 300px;
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
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div id="navcontent">
    </div>
    <div class="username">
        {{@SESSION.user.name}} {{@SESSION.user.surname}}
    </div>
</nav>

<h1>
    <div id="name"></div>
</h1>
<div id="categories"></div>

<check if="{{ @imageUrl }}"><true><img class="image" src="{{ @imageUrl }}" alt="Italian Trulli"></true></check>


<div id="content"></div>
<br><br>

<form id="commentForm">
    <input type="hidden" name="user" id="user" value="{{@SESSION.user.id}}">
    <label for="commentContent"><b>Napište komentář jako {{@SESSION.user.name}} {{@SESSION.user.surname}}: </b></label><br>
    <textarea class="textarea" name="commentContent" id="commentContent"></textarea>

    <input type="hidden" name="article" id="article" value="{{@id}}"><br>
    <input type="button" id="submitComment" class="button" value="Odeslat komentář">
</form>

<div id="commentsSection"></div>


<script>
    function loadArticle() {
        var articleId = window.location.pathname.split('/').pop(); // Získání ID článku z URL
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/writertest/getarticle/' + articleId); // Použití ID článku v URL
        xhr.onload = function() {
            if (this.status == 200) {
                var article = JSON.parse(this.responseText);
                document.getElementById('name').textContent = article.name;
                document.getElementById('content').textContent = article.content;

                loadComments(articleId);
                var categories = article.categories.join(', ');
                document.getElementById('categories').textContent = categories;
            } else {
                document.getElementById('name').textContent = "Nenalezeno";
                document.getElementById('content').textContent = "Nenalezeno";
                document.getElementById('categories').textContent = "";
            }
        };
        xhr.send();
    }

    function sendComment() {
        var xhr = new XMLHttpRequest();
        var formData = new FormData(document.getElementById('commentForm'));
        xhr.open('POST', '/writertest/make-comment/', true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                // Znovu načtení komentářů
                loadComments(window.location.pathname.split('/').pop());
            } else {
                alert('Nastala chyba při odesílání komentáře');
            }
        };
        xhr.send(formData);
    }


    function loadComments(articleId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/writertest/fetch-comments/' + articleId);
        xhr.onload = function() {
            if (this.status == 200) {
                var comments = JSON.parse(this.responseText);
                var commentsHtml = '';
                comments.forEach(function(comment) {
                    commentsHtml += '<div class="comment">';
                    commentsHtml += '<p><b>' + comment.userName + ':</b> ' + comment.content + '</p>';
                    commentsHtml += '</div>';
                });
                document.getElementById('commentsSection').innerHTML = commentsHtml;
            }else{
                document.getElementById('commentsSection').innerHTML = 'Zatím žádný komentář';
            }
        };
        xhr.send();
    }

    document.getElementById('submitComment').addEventListener('click', function(event) {
        event.preventDefault();
        sendComment();
    });

    window.onload = loadArticle;
</script>
</body>
</html>
