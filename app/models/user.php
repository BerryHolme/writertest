<?php

namespace models;

class user extends \DB\Cortex
{
    protected
        $db = 'DB',
        $table = 'user';
    protected $fieldConf=[
        'name'=>[
            'type'=>'VARCHAR256',
            'nullable'=>false
        ],
        'surname'=>[
            'type'=>'VARCHAR256',
            'nullable'=>false
        ],
        'email'=>[
            'type'=>'VARCHAR256',
            'nullable'=>false,
            'index' => true,
            'unique' => true,
        ],
        'password'=>[
            'type'=>'VARCHAR256',
            'nullable'=>false
        ],
        'role'=>[
            'belongs-to-one'=>'\models\role'
        ],
        'articles'=>[
            'has-many'=>array('\models\article','user')
        ]
    ];

    protected function set_password($value)
    {
        return password_hash($value,PASSWORD_DEFAULT);
    }
}