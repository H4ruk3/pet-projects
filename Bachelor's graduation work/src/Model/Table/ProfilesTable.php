<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class ProfilesTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('profiles');
        	$this->primaryKey('id');
    	}

    	public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('name', 'Не введено название профиля')
                ->notEmpty('sex', 'Не указан ваш пол')
                ->notEmpty('age', 'Не указан возраст')
                ->notEmpty('growth', 'Не указан рост')
                ->notEmpty('weight', 'Не указан вес')
                ->notEmpty('aimTrain', 'Не выбрана цель тренировки')
                ->notEmpty('somatotype', 'Не указан соматотип')
                ->add('age', 'validValue', [
                    'rule' => ['range', 1, 120],
                    'message' => 'Неверный возраст'
                ]);
        }

	}