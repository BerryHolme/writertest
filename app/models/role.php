<?php

namespace models;

use DB\Cortex;

class role extends Cortex
{
    protected $db = 'DB';
    protected $table = 'role';
    protected $primary = 'id';

    protected $fieldConf = [
        'name' => [
            'type'=>'VARCHAR256',
            'nullable'=>false
        ],
        'user'=>[
            'has-many'=>array('\models\user','role')
        ]
    ];
}
