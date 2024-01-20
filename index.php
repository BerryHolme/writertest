<?php
$f3 = require('lib/base.php');
$f3 = \Base::instance();
$f3->config("./app/configs/config.ini");
$f3->set('DB', new \DB\SQL('mysql:host=localhost;dbname=crm2024','root', ''));

// Zachytávání chyb, včetně chyby 404
$f3->set('ONERROR', function($f3, $params) {
    if ($f3->get('ERROR.code') == 404) {
        // Přesměrujte na vlastní stránku nebo kontroler pro chybu 404
        $f3->reroute('not-found/');
    }
    // Můžete zde přidat další obsluhy pro jiné typy chyb
});

$f3->run();
