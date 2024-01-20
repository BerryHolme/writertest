<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Seznam Uživatelů</title>
    <!-- Přidání jQuery pro zjednodušení AJAXových požadavků -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        $(document).ready(function() {
            $('.roleSelect').each(function() {
                $(this).data('original-role', $(this).val());
            });

            $('.roleSelect').change(function() {
                var originalRole = $(this).data('original-role');
                var currentRole = $(this).val();
                if (originalRole != currentRole) {
                    $(this).closest('tr').css('background-color', 'red');
                    $(this).closest('tr').css('color', 'white');
                } else {
                    $(this).closest('tr').css('background-color', '');
                    $(this).closest('tr').css('color', 'black');
                }
            });

            $('#submitChanges').click(function() {
                $('.roleSelect').each(function() {
                    var userId = $(this).data('user-id');
                    var roleId = $(this).val();
                    var originalRole = $(this).data('original-role');
                    if (roleId != originalRole) {
                        $.ajax({
                            url: 'update-role',
                            type: 'POST',
                            data: {userId: userId, roleId: roleId},
                            success: function(response) {
                                $('#user-' + userId).css('background-color', '');
                                $(this).closest('tr').css('color', 'black');
                                $('#user-' + userId + ' .roleSelect').data('original-role', roleId);
                                location.reload();
                            }
                        });
                    }
                });
            });
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

<check if="{{@SESSION.user.role == 1}}">
    <true>
        <h1>Seznam Uživatelů</h1>
        <p>Pokud dáte uživateli roli admin, už to nelze vrátit!</p>
        <button id="submitChanges" class="button">Odeslat změny</button><br><br>
        <table>
            <tr>
                <th>Jméno</th>
                <th>Email</th>
                <th>Role</th>
                <th>Změnit roli</th>
            </tr>
            <repeat group="{{@users}}" value="{{@user}}">
                <check if="{{@user.role.id != 1}}">
                    <true>
                        <tr id="user-{{@user.id}}">
                            <td>{{@user.name}} {{@user.surname}}</td>
                            <td>{{@user.email}}</td>
                            <td>{{@user.role.name}}</td>
                            <td>
                                <select class="roleSelect" data-user-id="{{@user.id}}">
                                    <repeat group="{{@roles}}" value="{{@role}}">

                                            <true>
                                                <option value="{{@role.id}}" {{@user.role.id == @role.id ? 'selected' : ''}}>{{@role.name}}</option>
                                            </true>

                                    </repeat>
                                </select>
                            </td>
                        </tr>
                    </true>
                </check>
            </repeat>
        </table>
    </true>
    <false>
        <h1>Nemáte přístup k této stránce</h1>
    </false>
</check>


</body>
</html>
