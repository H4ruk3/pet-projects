<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class EatingTable extends Table
{

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('eating');
        $this->primaryKey('id');

        $this->hasMany('routineeatingmenu', [
                'foreignKey' => 'eating_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);

        $this->belongsToMany ('Foods', array(
                    'className'              => 'Food',
                 'joinTable'              => 'routineeatingmenu',
                 'with'                   => '',
                'foreignKey'             => 'eating_id',
                'associationForeignKey'  => 'food_id',
                'unique'                 => true,
                )
            );    
        $this->hasOne('Routineday', ['className' => 'Routineday', 'foreignKey' => 'id', 'bindingKey' => 'routinedayId']);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('time', 'A time is required');
    }
}