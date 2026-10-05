<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;


class UsersTable extends Table
{

    public function initialize(array $config)
    {
        $this->hasMany('usergroup', [
                'className' => 'Usergroup',
                'foreignKey' => 'owner',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
        $this->hasMany('usergroupmembers', [
                'className' => 'Usergroup',
                'foreignKey' => 'member',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
        $this->hasMany('usernotification', [
                'className' => 'usernotification',
                'foreignKey' => 'uid',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
        $this->hasOne('snderuser', [
                'className' => 'usernotification',
                'foreignKey' => 'sender',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
        $this->hasOne('Profiles', [
                'className' => 'Profiles',
                'foreignKey' => 'userId',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
        $this->hasOne('Trainerprofile', [
                'className' => 'Trainerprofile',
                'foreignKey' => 'users_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);

    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('username', 'A username is required')
            ->add('username', 'validFormat', [
                'rule' => 'email',
                'message' => 'E-mail must be valid'
            ])
            ->notEmpty('password', 'A password is required')
            ->notEmpty('role', 'A role is required')
            ->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'user', 'trainer', 'corp']],
                'message' => 'Please enter a valid role'
            ]);
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username'], 'Пользователь с таким именем уже существует'));

        return $rules;
    }


    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        $query
            ->select(['id', 'username', 'password']);

        return $query;
    }

}
