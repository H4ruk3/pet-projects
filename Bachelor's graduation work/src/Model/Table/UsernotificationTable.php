<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class UsernotificationTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('usernotification');
        	$this->primaryKey('id');

            /*$this->belongsTo('users', [
                'foreignKey' => 'sender',
            ]);*/

            $this->belongsTo('senderuser', [
                'propertyName' => 'sender',
                'className' => 'Users',
                'foreignKey' => 'sender',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);

            $this->belongsTo('Users', [
                'propertyName' => 'user',
                'className' => 'Users',
                'foreignKey' => 'uid',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);            
    	}

	}