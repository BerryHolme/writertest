<?php

namespace controllers;

class userController
{
    public function getRegister(\Base $base)
    {
        echo \Template::instance()->render('register.php');

    }

    public function getLogin(\Base $base)
    {
        echo \Template::instance()->render('login.php');
    }

    public function postRegister(\Base $base)
    {   $user = $base->get("SESSION.user[id]");
        if ($user) {
            $base->clear("SESSION.user");
        }
        $user = new \models\user();

        $existingUser = $user->findone(["email=?", $base->get('POST.email')]);

        if ($existingUser) {
            echo \Template::instance()->render("register.php");
            return;
        }
        $user->copyfrom($base->get('POST'));

        $user->role = "3";

        $user->save();

        if($user->id==1){
            $user->role = "1";
            $user->save();
        }

        $base->reroute("/");

    }

    public function checkEmail(\Base $base)
    {
        $email = $base->get('POST.email');
        $user = new \models\user();
        $existingUser = $user->findone(["email=?", $email]);

        if ($existingUser) {
            echo json_encode(['exists' => true]);
        } else {
            echo json_encode(['exists' => false]);
        }
    }

    public function postLogin(\Base $base){
        $user = $base->get("SESSION.user[id]");
        if ($user) {
            $base->clear("SESSION.user");
        }

        $email = $base->get("POST.email");
        $user = new \models\user();
        $base->clear("SESSION.user");
        $u = $user->findone(["email=?", $email]);

        if ($u) {  // Check if userController exists
            if (password_verify($base->get('POST.password'), $u->password)) {
                $base->set("SESSION.user[id]", $u->id);
                $base->set("SESSION.user[name]", $u->name);
                $base->set("SESSION.user[surname]", $u->surname);
                $base->set("SESSION.user[email]", $u->email);
                $base->set("SESSION.user[role]", $u->role->id);
                $base->set("SESSION.user[rolename]", $u->role->name);

                $base->reroute("/");
            } else {
                echo \Template::instance()->render("login.php");
            }
        } else {
            echo \Template::instance()->render("login.php");
        }

    }

    public function checkLogin(\Base $base)
    {
        $user = $base->get("SESSION.user[id]");
        if ($user) {
            $response['loggedIn'] = true;
            $response['userName'] = $_SESSION['user']['name'];
            $response['userSurname'] = $_SESSION['user']['surname'];
            $response['userRole'] = $_SESSION['user']['role'];
        }else{
            $response = ['loggedIn' => false, 'userName' => '', 'userSurname' =>''];
        }
        echo json_encode($response);

    }

    public function logout(\Base $base)
    {
        $user = $base->get("SESSION.user[id]");
        if ($user) {
            $base->clear("SESSION.user");
        }
        $base->reroute('/');
    }

    public function checkPassword(\Base $base)
    {
        $email = $base->get("POST.email");
        $user = new \models\user();
        $base->clear("SESSION.user");
        $u = $user->findone(["email=?", $email]);

        if ($u) {
            if (password_verify($base->get('POST.password'), $u->password)) {
                echo json_encode(['exists' => true]);
            }else{
                echo json_encode(['exists' => false]);
            }
        }

    }

    public function getArticle(\Base $base, $params)
    {
        // Získání ID článku z URL
        $articleId = $params['id'];
        echo $articleId;
    }

    public function userTable(\Base $base)
    {
        if ($base->get("SESSION.user.role") != 1) {
            echo "Nemáte oprávnění přistupovat k této stránce.";
            return;
        }

        $user = new \models\user();
        $users = $user->find();

        $role = new \models\role();
        $roles = $role->find();

        $base->set('users', $users);
        $base->set('roles', $roles);

        echo \Template::instance()->render('usersTable.php');
    }


    public function updateRole(\Base $base)
    {
        if ($base->get("SESSION.user.role") != 1) {
            echo json_encode(['error' => 'Nemáte oprávnění k provedení této akce.']);
            return;
        }

        $userId = $base->get('POST.userId');
        $roleId = $base->get('POST.roleId');

        $user = new \models\user();
        $user->load(['id=?', $userId]);
        if ($user->role->id != 1) {
            $user->role = $roleId;
            $user->save();
            echo json_encode(['success' => 'Role byla úspěšně změněna.']);
        } else {
            echo json_encode(['error' => 'Nelze změnit roli administrátora.']);
        }
    }


}