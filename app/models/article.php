<?php

namespace models;

class article extends \DB\Cortex
{
    protected
        $db = 'DB',
        $table = 'article';
    protected $fieldConf=[
        'name'=>[
            'type'=>'VARCHAR256',
            'nullable'=>false
        ],
        'content'=>[
            'type'=>'TEXT',
            'nullable'=>false
        ],
        'category'=>[
            'belongs-to-one'=>'\models\category'
        ],
        'user'=>[
            'belongs-to-one'=>'\models\user'
        ]
    ];
}