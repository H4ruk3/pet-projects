<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class MembersComponent extends Component
{
    
    public function getMemberInfo($id) {
    
        $routines = TableRegistry::get("Routine")->find('all', ['conditions' => [ 'userId' => $id ]] )->count();
        $trainingprograms = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $id ] ])->count();
        $eatings = TableRegistry::get("Eatingprogram")->find('all', ['conditions' => [ 'users_id' => $id ] ])->count();
        $stat = (object) array('routine' => $routines, 'trainingprogram' => $trainingprograms, 'eatings' => $eatings);
        return $stat;
    
    }
     
}