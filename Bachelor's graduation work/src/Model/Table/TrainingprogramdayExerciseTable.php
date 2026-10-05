<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class TrainingprogramdayExerciseTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('trainingprogramday_exercise');
        	$this->primaryKey(['trainingprogramday_id', 'exercise_id']);
        	$this->hasOne('Exercise', ['className' => 'Exercise', 'foreignKey' => 'id', 'bindingKey' => 'exercise_id']);
    	}

	}