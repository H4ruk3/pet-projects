<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class TrainerprofileTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('trainerprofile');
        	$this->primaryKey('id');
    	}

    	public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('name', 'Не введено имя')
                ->notEmpty('fam', 'Не введено фамилия')
                ->notEmpty('otch', 'Не введено отчество')
                ->notEmpty('sex', 'Не указан ваш пол')
                ->notEmpty('birthday', 'Не указана дата рождения')
                ->notEmpty('city', 'Не указан город')      
                ->notEmpty('stage', 'Не указан стаж')
                ->notEmpty('club', 'Не указан клуб')
                ->notEmpty('trainingtype', 'Не указан тип тренировки');
                
        }

	}