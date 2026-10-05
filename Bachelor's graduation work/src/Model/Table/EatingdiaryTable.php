<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class EatingdiaryTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('eatingdiary');
        	$this->primaryKey('id');

        	/*$this->belongsTo('Trainingprogramday', array(
        		    'className'              => 'Trainingprogramday',
                'foreignKey'             => 'trainingprogramday_id',
                'associationForeignKey'  => 'id',
                'unique'                 => true )
        );*/
            $this->hasOne('Eatingprogram', ['className' => 'Eatingprogram', 'foreignKey' => 'id', 'bindingKey' => 'eatingprogram_id']);
            $this->hasOne('Food', ['className' => 'Food', 'foreignKey' => 'id', 'bindingKey' => 'food_id']);
            $this->hasOne('Eating', ['className' => 'Eating', 'foreignKey' => 'id', 'bindingKey' => 'eating_id']);

        }
    }