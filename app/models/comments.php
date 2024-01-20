<?php

namespace models;

class comments extends \DB\Cortex
{
    protected
        $db = 'DB',
        $table = 'comments';
    protected $fieldConf=[
        'id'=>[
            'type'=>'INT',
            'nullable'=>false
        ],

        'user'=>[
            'belongs-to-one'=>'\models\user'
        ],
        'content'=>[
            'type'=>'TEXT',
            'nullable'=>false
        ],
        'article'=>[
            'belongs-to-one'=>'\models\article'
        ]
    ];
}