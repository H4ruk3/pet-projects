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

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if ($this->Auth->user("role") == "corp" || $this->Auth->user("role") == "trainer")
        	$this->viewBuilder()->layout('corpuser');
        else if ($this->Auth->user("role") == "admin") 
			$this->viewBuilder()->layout('admin');
			else 	
        		$this->viewBuilder()->layout('redesignmain');
        $this->set("section", "training");
    }

	public function isAuthorized($user) {
        
		return true;  
    }

    private function validateInput() {
    	for ($i = 0; $i < count($_POST['exercise']); $i++) 
    		if (count($_POST['exercise'][$i+1]) == 0)
    			return false;
    	return true;
    }

	public function index() {
		$id = $this->Auth->user('id');

		$trainingprograms = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $id ] ]);
		$this->set("programs", $trainingprograms->all());

		$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true] ]);
		$this->set("aimTrain", $profiles->count()>0?$profiles->first()->aimTrain:null);

		if ($trainingprograms->count() > 0) {

			$trainingprogram = TableRegistry::get("Trainingprogram")
				->get($trainingprograms->first()->id, ['contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]]]);
			
			/*$trainingprograms = TableRegistry::get("Trainingprogram")->find('all', ['contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $id ] ]);
	*/
			$this->set("prog", $trainingprogram);
		}
		$trainingprograminfo = TableRegistry::get("Trainingprogram")
			->find('all', ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $id ]]);
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		if ($id != $admin->first()->id) {
			$templates = TableRegistry::get("Trainingprogram")
			->find('all', ['contain' => ['trainingprogramday'=>[
			'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $admin->first()->id ]]);
			$this->set("template", $templates->all());
		}
		$this->set("programsinfo", $trainingprograminfo->all());
		
	}

	public function active($id) {
		$uid = $this->Auth->user('id');
		$programs = TableRegistry::get("Trainingprogram");
		$program = $programs->get($id);
		TableRegistry::get("Trainingprogram")->updateAll(['active' => 0], ['users_id =' => $uid]);
		$program->active = true;
		$programs->save($program);
		$this->updateDiary($id, $uid);
		return $this->redirect(['action' => 'index']);	
	}  

	private function updateDiary($id, $uid) {
		$today = date("Y-m-d");
		$newtrainingdays = TableRegistry::get("Trainingprogramday")->find('all', ['conditions' => ['trainingprogram_id' => $id]])->toArray();
		$cc = count($newtrainingdays) - 1;
		$c = 0;
		$lasttraining = TableRegistry::get("Diary")->find('all', ['conditions' => ['date >=' => $today, 'users_id' => $uid], 'order' => ['date' => 'DESC']]);
		foreach($lasttraining as $key => $training) {
			//echo ($training->date."    ". $newtrainingdays[$c]->id.";     ");
			$training->trainingprogramday_id = $newtrainingdays[$c]->id;
			TableRegistry::get("Diary")->save($training);
			$c = $c<$cc?$c+1:0;
		}
	}

	public function view($id) {

		/*$trainingprogram = TableRegistry::get("Trainingprogram")
			->get($id, ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise']]]]);*/
$trainingprogram = TableRegistry::get("Trainingprogram")
			->get($id, ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]]]);

			//echo (json_encode($trainingprogram));	

/*         $trainingprogram = TableRegistry::get("Trainingprogram")
			->find('all', ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]] , "conditions" => ["users_id" => $this->Auth->user('id')]]);
*/
//echo($this->Auth->user('id'));
//echo (json_encode($trainingprogram));	            
		
		$this->set("program", $trainingprogram);
	}

	public function create() {

		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];

		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("musculgroups", $musculgroups);

		if ($_POST) {

			$id = $this->Auth->user('id');
			
			$programs = TableRegistry::get("Trainingprogram");
			$program = $programs->newEntity();
								
			$program->name = trim($_POST['name']);
			$program->aimTrain = $_POST['aimTrain'];
			$program->users_id = $id;
			
			$trainingdays = TableRegistry::get("Trainingprogramday");

			$validator = $programs->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			$validate = $this->validateInput();


			if (empty($errors) && $validate && $programs->save($program)) {
	
				for ($i = 0; $i < count($_POST['exercise']); $i++) {

					//$eatTime = date("H:i", mktime($_POST['eatTime'][$i]['hour'], $_POST['eatTime'][$i]['minute']));
					$trainingday = $trainingdays->newEntity();
					$trainingday->number = $i+1;
					$trainingday->trainingprogram_id = $program->id;
					$trainingdays->save($trainingday);
					//var_dump($_POST['excersice'][$i]);
					for ($j = 0; $j < count($_POST['exercise'][$i+1]); $j++) {
						$trainingdayexs = TableRegistry::get("Trainingprogramday_Exercise");
						$trainingdayex = $trainingdayexs->newEntity();
						$trainingdayex->trainingprogramday_id = $trainingday->id;
						$trainingdayex->exercise_id = (int)($_POST['exercise'][$i+1][$j+1]['excersiceid']);
						$trainingdayex->podhod = (int)$_POST['exercise'][$i+1][$j+1]['podhod'];
						$trainingdayex->repeats = (int)$_POST['exercise'][$i+1][$j+1]['repeat'];
						//$trainingdayex->weight = (float)$_POST['exercise'][$i+1][$j+1]['weight'];
						$trainingdayex->minweight = (float)$_POST['exercise'][$i+1][$j+1]['minweight'];
						$trainingdayex->maxweight = (float)$_POST['exercise'][$i+1][$j+1]['maxweight'];
						$trainingdayex->position = $j+1;
						$trainingdayexs->save($trainingdayex);
						//$trainingdayex->
					}
				}

				return $this->redirect(['action' => 'index']);
			}
			else {
				$this->set("error", "empty day");
			}		
		}
	}

	public function createtmp($tpid) {

		if ($tpid != null)
		{
			$trainingprogram = TableRegistry::get("Trainingprogram")
			->get($tpid, ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise']]]]);
		$this->set("program", $trainingprogram);
	} else $this->set("program", null);
	$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];

		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("musculgroups", $musculgroups);

		if ($_POST) {

			$id = $this->Auth->user('id');
			
			$programs = TableRegistry::get("Trainingprogram");
			$program = $programs->newEntity();
								
			$program->name = trim($_POST['name']);
			$program->aimTrain = $_POST['aimTrain'];
			$program->users_id = $id;
			
			$trainingdays = TableRegistry::get("Trainingprogramday");

			$validator = $programs->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			$validate = $this->validateInput();


			if (empty($errors) && $validate && $programs->save($program)) {
	
				for ($i = 0; $i < count($_POST['exercise']); $i++) {

					//$eatTime = date("H:i", mktime($_POST['eatTime'][$i]['hour'], $_POST['eatTime'][$i]['minute']));
					$trainingday = $trainingdays->newEntity();
					$trainingday->number = $i+1;
					$trainingday->trainingprogram_id = $program->id;
					$trainingdays->save($trainingday);
					//var_dump($_POST['excersice'][$i]);
					for ($j = 0; $j < count($_POST['exercise'][$i+1]); $j++) {
						$trainingdayexs = TableRegistry::get("Trainingprogramday_Exercise");
						$trainingdayex = $trainingdayexs->newEntity();
						$trainingdayex->trainingprogramday_id = $trainingday->id;
						$trainingdayex->exercise_id = (int)($_POST['exercise'][$i+1][$j+1]['excersiceid']);
						$trainingdayex->podhod = (int)$_POST['exercise'][$i+1][$j+1]['podhod'];
						$trainingdayex->repeats = (int)$_POST['exercise'][$i+1][$j+1]['repeat'];
						//$trainingdayex->weight = (float)$_POST['exercise'][$i+1][$j+1]['weight'];
						$trainingdayex->minweight = (float)$_POST['exercise'][$i+1][$j+1]['minweight'];
						$trainingdayex->maxweight = (float)$_POST['exercise'][$i+1][$j+1]['maxweight'];
						$trainingdayex->position = $j+1;
						$trainingdayexs->save($trainingdayex);
						//$trainingdayex->
					}
				}

				return $this->redirect(['action' => 'index']);
			}
			else {
				$this->set("error", "empty day");
			}		
		}
		$this->render('create');
	}

	public function edit($id) {

		$uid = $this->Auth->user('id');

		$trainingprogram = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $uid, "id" => $id ], 'contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise']]] ])->first();

        /*$trainingprogram = TableRegistry::get("Trainingprogram")
			->get($id, ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]]]);*/

		/*$trainingprogram = TableRegistry::get("Trainingprogram")
			->get($id, ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise']]]]);*/
           if ($trainingprogram == null) 
           	throw new NotFoundException();
		$this->set("program", $trainingprogram);
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];

		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("musculgroups", $musculgroups);

		//$routine = TableRegistry::get("Routine")->get($id, ["contain" => ["Eats"]]);

		if ($_POST) {

			$id = $this->Auth->user('id');
			$connection = \Cake\Datasource\ConnectionManager::get("default");
			$connection->logQueries(true);
			$programs = TableRegistry::get("Trainingprogram");
								
			$trainingprogram->name = $_POST['name'];
			$trainingprogram->userId = $id;
			
			$trainingdays = TableRegistry::get("Trainingprogramday");
			$trainingprogramdaysexercises = TableRegistry::get("Trainingprogramday_Exercise");

			$validator = $programs->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			$validate = $this->validateInput();

			if ($validate) {
			if ($programs->save($trainingprogram)) {
	
				$trainingday = $trainingdays->find('all', ['conditions'=>['trainingprogram_id' => $trainingprogram->id]]);
				foreach ($trainingday as $id => $day) {
					$trainingprogramdaysexercises->deleteAll([ 'trainingprogramday_id' => $day->id ]);		
				}
				/*$trainingdays->deleteAll([ 'trainingprogram_id' => $trainingprogram->id ]);*/
				$days = $trainingdays->find('all', ['conditions' => ['trainingprogram_id' => $trainingprogram->id ]]);
				$days = $days->toArray();

				for ($i = 0; $i < count($_POST['exercise']); $i++) {

					//$eatTime = date("H:i", mktime($_POST['eatTime'][$i]['hour'], $_POST['eatTime'][$i]['minute']));
					$trainingday = null;
					if (count($days)>$i) {
						$trainingday = $days[$i];
					} else {
						$trainingday = $trainingdays->newEntity();
						$trainingday->number = $i+1;
						$trainingday->trainingprogram_id = $trainingprogram->id;
						$trainingdays->save($trainingday);
					}
					//var_dump($_POST['excersice'][$i]);
					for ($j = 0; $j < count($_POST['exercise'][$i+1]); $j++) {
						$trainingdayexs = TableRegistry::get("Trainingprogramday_Exercise");
						$trainingdayex = $trainingdayexs->newEntity();
						$trainingdayex->trainingprogramday_id = $trainingday->id;
						$trainingdayex->exercise_id = (int)($_POST['exercise'][$i+1][$j+1]['excersiceid']);
						$trainingdayex->podhod = (int)$_POST['exercise'][$i+1][$j+1]['podhod'];
						$trainingdayex->repeats = (int)$_POST['exercise'][$i+1][$j+1]['repeat'];
						$trainingdayex->minweight = (float)$_POST['exercise'][$i+1][$j+1]['minweight'];
						$trainingdayex->maxweight = (float)$_POST['exercise'][$i+1][$j+1]['maxweight'];
						$trainingdayex->position = $j+1;
						$trainingdayexs->save($trainingdayex);
						//$trainingdayex->
					}
				}
				$connection->logQueries(false);

				return $this->redirect(['action' => 'index']);
			}
			else {$this->set("error", "empty day");}

				/*for ($i = 0; $i < count($_POST['eatTime']); $i++) {

					$eatTime = date("H:i", mktime($_POST['eatTime'][$i]['hour'], $_POST['eatTime'][$i]['minute']));
					$eatTable = $eatsTable->newEntity();
					$eatTable->time = $eatTime;
					$eatTable->routineId = $routine->id;
					$eatsTable->save($eatTable);
				}*/

				//return $this->redirect(['action' => 'table']);	
			}		
		}
		$this->render('create');

		//$this->set("routine", $routine);
	}

	/*public function table() {

		$id = $this->Auth->user('id');

		$routines = TableRegistry::get("Routine")->find('all', [ 'conditions' => [ 'userId' => $id ] ]);
		
		$this->set("routines", $routines);
	}


	public function get($id) {

		$routine = TableRegistry::get("Routine")->get($id);


		$this->set("routine", $routine);
	}*/

	public function delete($id) {

		$uid = $this->Auth->user('id');

		$this->autoRender = false;

		$programs = TableRegistry::get("Trainingprogram");
		$trainingdays = TableRegistry::get("Trainingprogramday");
		$trainingprogramdaysexercises = TableRegistry::get("Trainingprogramday_Exercise");
		$program = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $uid, "id" => $id ], 'contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise']]] ])->first();
		//$programs->delete($program);
		if ($program == null)
			throw new NotFoundException();
		$trainingday = $trainingdays->find('all', ['conditions'=>['trainingprogram_id' => $program->id]]);
				foreach ($trainingday as $id => $day) {
					$trainingprogramdaysexercises->deleteAll([ 'trainingprogramday_id' => $day->id ]);		
				}
				$trainingdays->deleteAll([ 'trainingprogram_id' => $id ]);

		$programs->delete($program);
		return $this->redirect(['action' => 'index']);
	}

}