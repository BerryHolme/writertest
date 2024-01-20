<?php


namespace controllers;

use models\comments;

class index
{
    public function index(\Base $base)
    {
        echo \Template::instance()->render('home.php');
        
    }
    private function addRole(){
        $mam = ["Admin","Writer","Reader","Visitor"];
        foreach ($mam as $zaznam){
            $role = new \models\role();
            $role->name = $zaznam;
            $role->save();
            unset($role);
        }
    }


    public function install(\Base $base)
    {
         if (password_verify($base->get('POST.password'), '$2y$10$XMTpWHKBrHJexipdytzwVuOx40m2lYvxhqBnw1xqv9UHPC4XIXE7q')){
             \models\article::setdown();
             \models\role::setdown();
             \models\category::setdown();
             \models\user::setdown();
             \models\comments::setdown();

             \models\article::setup();
             \models\role::setup();
             \models\category::setup();
             \models\user::setup();
             \models\comments::setup();

             $this->addRole();

             $user = $base->get("SESSION.user[id]");
             if ($user) {
                 $base->clear("SESSION.user");
             }

             $folderPath = 'articleImages';
             if (is_dir($folderPath)) {
                 $files = glob($folderPath . '/*'); // Získání všech souborů ve složce
                 foreach ($files as $file) {
                     if (is_file($file)) {
                         unlink($file); // Odstranění souboru
                     }
                 }
             } else {
                 echo "Zadaná cesta není složka nebo neexistuje.";
             }

             echo 'Install proběhl úspěšně!';
             echo '<br><button onclick="window.location.href=\'/writertest\'">Domů</button>';
         }else{
             echo 'Špatné heslo!';
         }
    }

    public function getInstall(\Base $base)
    {
        echo \Template::instance()->render('install.php');

    }

    public function notFound(\Base $base)
    {
        $user = $base->get("SESSION.user[id]");
        if ($user) {
            echo \Template::instance()->render("notfoundlogedin.php");
        }else{
            echo \Template::instance()->render("notfound.php");
        }

    }

}