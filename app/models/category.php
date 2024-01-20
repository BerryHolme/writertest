<?php

namespace models;

class category extends \DB\Cortex
{
    protected
        $db = 'DB',
        $table = 'category';
    protected $fieldConf=[
        'id'=>[
            'type'=>'INT',
            'nullable'=>false
        ],

        'name'=>[
            'type'=>'VARCHAR256',
            'nullable'=>false
        ],
        'description'=>[
            'type'=>'TEXT',
            'nullable'=>false
        ],
        'articles'=>[
            'has-many'=>array('\models\article','category')
        ]
    ];
}