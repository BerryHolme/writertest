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

                        links += "<h1> {{@SESSION.user.name}} {{@SESSION.user.surname}} se asi ztratil. Ale neboj i tak pro tebe něco máme.</h1> <br> <a href='/writertest'>Domů</a> <a href='/writertest/account/'>Účet</a>";
                        if (response.userRole == 1 || response.userRole == 2) {
                            links += " <a href='/writertest/new-article/'>Nový článek</a> <a href='/writertest/newcategory/'>Nová kategorie</a>";
                        }
                        links += " <a href='/writertest/categories/'>Seznam kategorií</a> <a href='/writertest/logout/'>Odhlásit</a>";
                        contentDiv.innerHTML = links;
                    } else {
                        contentDiv.innerHTML = "<h1>Ops tady se někdo ztratil. Ale neboj něco pro tebe máme.</h1> <br> <a href='/writertest'>Domů</a> <a href='/writertest/login/'>Přihlásit se</a> <a href='/writertest/register/'>Registrovat se</a>";
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


</nav>

ERROR 404

<iframe width="100%" height="977" src="https://www.youtube.com/embed/4t7BgyA7IOI?autoplay=1" title="Toothless Dancing To Driftveil City 10 Hours" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>


</body>
</html>
