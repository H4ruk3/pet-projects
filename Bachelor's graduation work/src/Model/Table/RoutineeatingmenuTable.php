<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class RoutineeatingmenuTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('routineeatingmenu');
        	$this->primaryKey(['eating_id', 'food_id', 'eatingprogram_id', 'day_number']);

        	$this->hasOne('Food', ['className' => 'Food', 'foreignKey' => 'id', 'bindingKey' => 'food_id']);
    		$this->hasOne('Eating', ['className' => 'Eating', 'foreignKey' => 'id', 'bindingKey' => 'eating_id']);
    	}



	}