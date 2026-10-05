<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class UsergroupTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('usergroup');
        	$this->primaryKey('id');

            $this->belongsTo('member', [
                'propertyName' => 'member',
                'className' => 'Users',
                'foreignKey' => 'member',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
    	}

	}