<?php

namespace App\Controller;

use User;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validator;


class TrainingprogramController extends AppController
{
	function getplantraining() {
		$this->autoRender = false;
		$uid = $this->Auth->user('id');
		$members = TableRegistry::get("Users")
				->find('all', ['contain' => ['Profiles'], 'conditions' => [ 'trainer' => $uid, 'active' => true]]);
		$alldays = array();
		foreach($members as $member) {
			$diarydays = TableRegistry::get("Diary")->find('all', [ 'conditions' => [ 'users_id' => $id ] ])->toArray();
			array_push($alldays, $diarydays);
		}
		echo json_encode($alldays);
	}
}