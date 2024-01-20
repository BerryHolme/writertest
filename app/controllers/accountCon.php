<?php

namespace controllers;

class accountCon
{
    public function getAccount(\Base $base)
    {
        $user = $base->get("SESSION.user[id]");
        if($user){
            echo \Template::instance()->render('account.php');
        }else{
            $base->reroute('not-found/');
        }
    }



    public function newPassword(\Base $base)
    {
        $sessionUserId = $base->get("SESSION.user[id]");
        if (!$sessionUserId) {
            echo json_encode(['state' => false]);
            return;
        }

        $email = $base->get("SESSION.user[email]");
        $oldPassword = $base->get('POST.old');
        $newPassword = $base->get('POST.new');

        $user = new \models\user();
        $u = $user->findone(["email=?", $email]);

        if ($u && $u->id == $sessionUserId) {
            if (password_verify($oldPassword, $u->password)) {
                $u->password = $newPassword;
                $u->save();
                echo json_encode(['state' => true]);
            } else {
                echo json_encode(['state' => false]);
            }
        } else {
            echo json_encode(['state' => false]);
        }
    }

}