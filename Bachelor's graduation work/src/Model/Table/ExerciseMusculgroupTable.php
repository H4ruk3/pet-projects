<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class ExerciseMusculgroupTable extends Table
	{
		public function initialize(array $config)
		{
			parent::initialize($config);

			$this->table('exercise_musculgroup');

			$this->primaryKey([
				'musculgroup_id',
				'exercise_id'
			]);
		}
	}