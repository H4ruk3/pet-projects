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
use Cake\Controller\Component\PaginatorComponent;


class RoutineController extends AppController
{
	public $paginate = [
        'limit' => 10,
        'order' => [
            'Routine.id' => 'asc'
        ]
    ];	

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //var_dump($this->request->admin);

        if ($this->Auth->user("role") == "corp" || $this->Auth->user("role") == "trainer")
        	$this->viewBuilder()->layout('corpuser');
        else	//if ($this->Auth->user("role") == "admin") 
        	if ($this->request->admin)
        		if ($this->Auth->user("role") == "admin") 
					$this->viewBuilder()->layout('admin');
				else 
					throw new UnauthorizedException();
			else 	

        	$this->viewBuilder()->layout('redesignmain');
        $this->set("section", "routine");
    }

	public function isAuthorized($user) {
        
		return true;  
    }

	public function active($id) {
		$uid = $this->Auth->user('id');
		$routines = TableRegistry::get("Routine");
		$routine = $routines->get($id);
		TableRegistry::get("Routine")->updateAll(['active' => 0], ['userId =' => $uid]);
		$routine->active = true;
		$routines->save($routine);
		return $this->redirect(['action' => 'index']);	
	}   

    /*public function active() {

		$this->autoRender = false;

		if ($this->request->is('post')) {
			$id = $this->Auth->user('id');
			$r = $this->request->input();
			//var_dump($r);
			$data = json_decode($this->request->input());
			//var_dump($data);
			$routines = TableRegistry::get("Routine");
			//var_dump($this->request->input());
			$routine = $routines->get($data->routine);
			TableRegistry::get("Routine")->updateAll(['active' => 0], ['userId =' => $id]);
			$routine->active = true;
			//var_dump($routine);
			$routines->save($routine);

			echo $this->ok();
		}
	}
*/
	public function index() {
		$id = $this->Auth->user('id');

		$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id ]] );
		
		$this->set("routines", $this->paginate($routines));

	}

	public function view($id) {

		$model = TableRegistry::get("Routine")->get($id, ["contain" => ["Eating"]]);

		
		$this->set("model", $model);
	}

	public function create() {

		if ($_POST) {

			//var_dump($_POST);

			$id = $this->Auth->user('id');
			//Получаем время
			$day1times = array();
			list($h, $m) = explode(":", $_POST['day'][0]['wakeupTime']);
			$day1times['wakeup'] = date("H:i", mktime($h, $m));
			list($h, $m) = explode(":", $_POST['day'][0]['trainTime']);
			$day1times['train'] = date("H:i", mktime($h, $m));
			list($h, $m) = explode(":", $_POST['day'][0]['trainDuration']);
			$day1times['duration'] = date("H:i", mktime($h, $m));
			list($h, $m) = explode(":", $_POST['day'][0]['sleepTime']);
			$day1times['sleep'] = date("H:i", mktime($h, $m));
			$day2times = array();
			list($h, $m) = explode(":", $_POST['day'][1]['wakeupTime']);
			$day2times['wakeup'] = date("H:i", mktime($h, $m));
			list($h, $m) = explode(":", $_POST['day'][1]['sleepTime']);
			$day2times['sleep'] = date("H:i", mktime($h, $m));

			//Сохраняем распорядок дня
			$routines = TableRegistry::get("Routine");
			$routine = $routines->newEntity();
			$routine->name = trim($_POST['name']);
			$routine->userId = $id;
			$validator = $routines->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());

			if (empty($errors) && $routines->save($routine)) {
				//Сохраняем данные первого дня.
				$routinedays = TableRegistry::get("Routineday");
				$routineday = $routinedays->newEntity();
				$routineday->type = 1;
				$routineday->eatCount = $_POST['day'][0]['eatCount'];
				$routineday->wakeupTime = $day1times['wakeup'];
				$routineday->trainTime = $day1times['train'];
				$routineday->sleepTime = $day1times['sleep'];
				$routineday->trainDuration = $day1times['duration'];
				$routineday->routineId = $routine->id;
				//$validator = $routinedays->validationDefault(new Validator());
				//$errors = $validator->errors($routineday);
				$routinedays->save($routineday);
				$eatsTable = TableRegistry::get("Eating");
				for ($i = 0; $i < count($_POST['day'][0]['eatTime']); $i++) {

					list($h, $m) = explode(":", $_POST['day'][0]['eatTime'][$i]);
					$eatTime = date("H:i", mktime($h, $m));
					$eatTable = $eatsTable->newEntity();
					$eatTable->time = $eatTime;
					$eatTable->routineId = $routine->id;
					$eatTable->routinedayId = $routineday->id;
					$eatsTable->save($eatTable);
				}
				//Сохраняем данные второго дня.
				$routinedays = TableRegistry::get("Routineday");
				$routineday = $routinedays->newEntity();
				$routineday->type = 2;
				$routineday->eatCount = $_POST['day'][1]['eatCount'];
				$routineday->wakeupTime = $day2times['wakeup'];
				$routineday->sleepTime = $day1times['sleep'];
				$routineday->routineId = $routine->id;
				//$validator = $routinedays->validationDefault(new Validator());
				//$errors = $validator->errors($routineday);
				$routinedays->save($routineday);
				$eatsTable = TableRegistry::get("Eating");
				for ($i = 0; $i < count($_POST['day'][1]['eatTime']); $i++) {

					list($h, $m) = explode(":", $_POST['day'][1]['eatTime'][$i]);
					$eatTime = date("H:i", mktime($h, $m));
					$eatTable = $eatsTable->newEntity();
					$eatTable->time = $eatTime;
					$eatTable->routineId = $routine->id;
					$eatTable->routinedayId = $routineday->id;
					$eatsTable->save($eatTable);
				}

				return $this->redirect(['action' => 'index']);
			}
			else {
				$this->set("errors", $errors);
				$errstr = "";
				foreach ($errors as $id => $error) {
					$errstr = $errstr . implode("\n", $error)."\n";		
				}
				$this->Flash->error($errstr);
			}
			$this->set("data", $_POST);

		}
	}

	public function edit($id) {

		$routine = TableRegistry::get("Routine")->get($id, ['contain' => ['Routineday'=>['Eating']]]);

		if ($_POST) {

			//var_dump($_POST);

			$id = $this->Auth->user('id');
			//Получаем время
			$day1times = array();
			list($h, $m) = explode(":", $_POST['day'][0]['wakeupTime']);
			$day1times['wakeup'] = date("H:i", mktime($h, $m));
			list($h, $m) = explode(":", $_POST['day'][0]['trainTime']);
			$day1times['train'] = date("H:i", mktime($h, $m));
			list($h, $m) = explode(":", $_POST['day'][0]['trainDuration']);
			$day1times['duration'] = date("H:i", mktime($h, $m));
			list($h, $m) = explode(":", $_POST['day'][0]['sleepTime']);
			$day1times['sleep'] = date("H:i", mktime($h, $m));
			$day2times = array();
			list($h, $m) = explode(":", $_POST['day'][1]['wakeupTime']);
			$day2times['wakeup'] = date("H:i", mktime($h, $m));
			list($h, $m) = explode(":", $_POST['day'][1]['sleepTime']);
			$day2times['sleep'] = date("H:i", mktime($h, $m));

			$routines = TableRegistry::get("Routine");
			$routine->name = $_POST['name'];
			$routine->userId = $id;
			$id1 = $routine->routineday[0]->id;
			$id2 = $routine->routineday[1]->id;
			
			if (empty($errors) && $routines->save($routine)) {
				//Сохраняем данные первого дня.
				$eatsTable = TableRegistry::get("Eating");
				//$eatsTable->deleteAll([ 'routinedayId' => $routine->routineday[0]->id ]);
				//$eatsTable->deleteAll([ 'routinedayId' => $routine->routineday[1]->id ]);

				$routinedays = TableRegistry::get("Routineday");
				/*Не удаляем по возможности существующие данные при обновлении*/
				//$routinedays->deleteAll( ['routineId' => $routine->id ]);
				/*$routineday = $routinedays->find("all", ["conditions" => ['routineId' => $routine->id, "type" => 1 ]]);
				//$routineday = $routinedays->newEntity();
				if ($routineday->count() > 0)
					$routineday = $routineday-> ->first();
				else 
					$routineday = $routinedays->newEntity();*/
				$routineday = $routinedays->get($routine->routineday[0]->id);
				$routineday->type = 1;
				$routineday->eatCount = $_POST['day'][0]['eatCount'];
				$routineday->wakeupTime = $day1times['wakeup'];
				$routineday->trainTime = $day1times['train'];
				$routineday->sleepTime = $day1times['sleep'];
				$routineday->trainDuration = $day1times['duration'];
				$routineday->routineId = $routine->id;
				//$validator = $routinedays->validationDefault(new Validator());
				//$errors = $validator->errors($routineday);
				$routinedays->save($routineday);
				$eatsTable = TableRegistry::get("Eating");
				$existingeats = $eatsTable->find('all', ["conditions" => ['routinedayId' => $routine->routineday[0]->id ]]);
				$existingeats = $existingeats->toArray();
				//print_r($existingeats); echo "<br>";

				for ($i = 0; $i < count($_POST['day'][0]['eatTime']); $i++) {

					list($h, $m) = explode(":", $_POST['day'][0]['eatTime'][$i]);
					$eatTime = date("H:i", mktime($h, $m));
					$eatTable = [];
					if ($i<count($existingeats)) {
						$eatTable = $existingeats[$i];
						//echo $eatTable;
					}
					else	
						$eatTable = $eatsTable->newEntity();
					$eatTable->time = $eatTime;
					$eatTable->routineId = $routine->id;
					$eatTable->routinedayId = $routineday->id;
					//echo "<br>"; print_r($eatTable);
					$eatsTable->save($eatTable);
				}
				if (count($existingeats) > count($_POST['day'][0]['eatTime']))
					for ($i = count($_POST['day'][0]['eatTime']); $i < count($existingeats); $i++) {
						$eatsTable->delete($existingeats[$i]);
					}

				//Сохраняем данные второго дня.
				$routinedays = TableRegistry::get("Routineday");
				//$routineday = $routinedays->newEntity();
				if (isset($routine->routineday[1]) && ($routine->routineday[1] != NULL))
					$routineday = $routinedays->get($routine->routineday[1]->id);
				else
					$routineday = $routinedays->newEntity();
				$routineday->type = 2;
				$routineday->eatCount = $_POST['day'][1]['eatCount'];
				$routineday->wakeupTime = $day2times['wakeup'];
				$routineday->sleepTime = $day1times['sleep'];
				$routineday->routineId = $routine->id;
				//$validator = $routinedays->validationDefault(new Validator());
				//$errors = $validator->errors($routineday);
				$routinedays->save($routineday);
				$eatsTable = TableRegistry::get("Eating");
				if (isset($routine->routineday[1]) && ($routine->routineday[1] != NULL)) {
					$existingeats = $eatsTable->find('all', ["conditions" => ['routinedayId' => $routine->routineday[1]->id ]]);
					$existingeats = $existingeats->toArray();
				} else
					$existingeats = [];
				for ($i = 0; $i < count($_POST['day'][1]['eatTime']); $i++) {

					list($h, $m) = explode(":", $_POST['day'][1]['eatTime'][$i]);
					$eatTime = date("H:i", mktime($h, $m));
					$eatTable = [];
					if (count($i<$existingeats))
						$eatTable = $existingeats[$i];
					else	
						$eatTable = $eatsTable->newEntity();
					//$eatTable = $eatsTable->newEntity();
					$eatTable->time = $eatTime;
					$eatTable->routineId = $routine->id;
					$eatTable->routinedayId = $routineday->id;
					$eatsTable->save($eatTable);
				}
				if (count($existingeats) > count($_POST['day'][1]['eatTime']))
					for ($i = count($_POST['day'][1]['eatTime']); $i < count($existingeats); $i++) {
						$eatsTable->delete($existingeats[$i]);
					}



			/*$routine->eatCount = $_POST['eatCount'];
			$routine->wakeupTime = $wakeup;
			$routine->trainTime = $train;
			$routine->sleepTime = $sleep;
			*/

			/*if ($_POST['active']) {

				TableRegistry::get("Routine")->updateAll(['active' => 0], ['userId =' => $id]);
				$routine->active = $_POST['active'];
			}*/
			
			/*$eatsTable = TableRegistry::get("Eating");

			if ($routines->save($routine)) {
	
				$eatsTable->deleteAll([ 'routineId' => $routine->id ]);				

				for ($i = 0; $i < count($_POST['eatTime']); $i++) {

					list($h, $m) = explode(":", $_POST['eatTime'][$i]);
					$eatTime = date("H:i", mktime($h, $m));
					$eatTable = $eatsTable->newEntity();
					$eatTable->time = $eatTime;
					$eatTable->routineId = $routine->id;
					$eatsTable->save($eatTable);
				}*/

				return $this->redirect(['action' => 'index']);	
			}		
		}

		$this->set("routine", $routine);
		$this->render('create');	
	}

	public function table() {

		$id = $this->Auth->user('id');

		$routines = TableRegistry::get("Routine")->find('all', [ 'conditions' => [ 'userId' => $id ] ]);
		
		$this->set("routines", $routines);
	}


	public function get($id) {

		$routine = TableRegistry::get("Routine")->get($id);


		$this->set("routine", $routine);
	}

	public function delete($id) {

//		var_dump($id);
		//$this->autoRender = false;

		$routines = TableRegistry::get("Routine");
		$routine = $routines->get($id);
		$routinedays = TableRegistry::get("Routineday");
		$days = $routinedays->find('all', ['conditions' => [ 'routineId' => $id ]] );
		if ($days->count() > 0)
			foreach ($days as $day) {
	    		$eatstable = TableRegistry::get("Eating");
	    		$eats = $eatstable->find('all', ['conditions' => [ 'routinedayId' => $day->id ]] );
	    		if ($eats->count() > 0)
		    		foreach ($eats as $eat) {
		    			$eatstable->delete($eat);
		    		}
	    		$routinedays->delete($day);
			}
		$routines->delete($routine);
		return $this->redirect(['action' => 'index']);	
	}

}