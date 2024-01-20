<?php
$f3 = require('lib/base.php');
$f3 = \Base::instance();
$f3->config("./app/configs/config.ini");

try {
    $f3->set('DB', new \DB\SQL('mysql:host=localhost;dbname=writertest','root', ''));
} catch (\PDOException $e) {
    if ($e->getCode() == 1049) {
        echo \Template::instance()->render('noDatabase.php');
    }
    throw $e;
}

$f3->set('ONERROR', function($f3, $params) {
    if ($f3->get('ERROR.code') == 404) {
        $f3->reroute('not-found/');
    }
});

$f3->run();
