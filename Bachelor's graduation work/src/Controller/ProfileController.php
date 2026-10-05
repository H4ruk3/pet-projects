<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component\MembersComponent;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validator;

class ProfileController extends AppController
{


	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if ($this->Auth->user("role") == "corp" || $this->Auth->user("role") == "trainer")
        	$this->viewBuilder()->layout('corpuser');
        else	
        	$this->viewBuilder()->layout('redesignmain');
        $this->set("section", "profile");
    }

    public function isAuthorized($user) {
        
        return true;
    }

    /************************************************************
    Стартовая странца профиля. Выводим статистику.
    ************************************************************/
    public function index() {
    	$id = $this->Auth->user('id');
    	/*Получаем информацию об активных запросах*/
    	$messages = TableRegistry::get("Usernotification")->find('all', [ 'contain' => ['senderuser'], 'conditions' => [ 'uId' => $id, 'status' => false] ]);
    	if ($messages->count() > 0)
    		$this->set("message", $messages);

		$profiles = array();
		if ($this->Auth->user('role') == "trainer") {
			$profiles = TableRegistry::get("TrainerProfile")->find('all', [ 'conditions' => [ 'Trainerprofile.users_id' => $id, 'Trainerprofile.active' => true] ]);
		} else 
			$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'Profiles.userId' => $id, 'Profiles.active' => true] ]);
		if ($profiles->count() > 0) {
			$this->set("profile", true);
			$p = $profiles->first();
			// Для тренера подгружаем trainerprofile и мёрджим личные данные из profiles
			if ($this->Auth->user('role') == 'trainer') {
				$trainerRow = TableRegistry::get("Trainerprofile")->find('all', ['conditions' => ['users_id' => $id]])->first();
				if ($trainerRow) {
					$p->city        = $trainerRow->city;
					$p->club        = $trainerRow->club;
					$p->stage       = $trainerRow->stage;
					$p->trainingtype = $trainerRow->trainingtype;
				}
				$physProfile = TableRegistry::get("Profiles")->find('all', ['conditions' => ['userId' => $id]])->first();
				if ($physProfile) {
					$p->name       = $physProfile->name;
					$p->fam        = $physProfile->fam;
					$p->otch       = $physProfile->otch;
					$p->sex        = $physProfile->sex;
					$p->birthday   = $physProfile->birthday;
					$p->growth     = $physProfile->growth;
					$p->weight     = $physProfile->weight;
					$p->aimTrain   = $physProfile->aimTrain;
					$p->somatotype = $physProfile->somatotype;
				}
			}
			if ($this->Auth->user("trainer") != null && $this->Auth->user("trainer") != 0) {
				$p->trainer = TableRegistry::get("Users")->find("all", array("conditions" => array("Users.id" => $this->Auth->user("trainer")), "contain" => array("Trainerprofile")))->first();
			}
			$p->age = $p->birthday ? $this->getFullYears($p->birthday) : 0;
			$p->username = $this->Auth->user('username');
			$p->role = $this->Auth->user('role');
			$usergroups = TableRegistry::get("Usergroup")->find('all', [ 'conditions' => ['owner_id' => $id]]);
			if ($usergroups->count() > 0) {
				$userid = $usergroups->first();
				$user = TableRegistry::get("Users")->get($userid->owner);
				$p->corpuser = $user->username;
			}
			$members = TableRegistry::get("Users")->find("all", array("conditions" => array("Users.trainer" => $this->Auth->user("id"), "Users.active" => true), "contain" => array("Profiles")));
			$this->set("members", $members);
			$this->loadComponent('Members');
			foreach ($members as $key => $value) {
				$value->stat = $this->Members->getMemberInfo($value->id);
			}

			$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id ]] )->count();
			$currentroutine = null;
			if ($routines > 0)
			$currentroutine = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'Routine.userId' => $id, 'Routine.active' => 1 ] ])->first();
			$trainingprograms = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $id ] ])->count();
			$currentprogram = null;
			if (TableRegistry::get("Trainingprogram")
				->find('all', ['contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $id, "active" => 1 ] ])->count() > 0)
			$currentprogram = TableRegistry::get("Trainingprogram")
				->find('all', ['contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $id, "active" => 1 ] ])->first();
			$eatings = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine'], 'conditions' => [ 'users_id' => $id ] ])->count();
			$eatingprogram = null;
			if ($currentroutine!=null && TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $id, 'routine_id' => $currentroutine->id, "Eatingprogram.active" => 1] ])->count() > 0)
			$eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $id, 'routine_id' => $currentroutine->id, "Eatingprogram.active" => 1] ])->first();

			
			$stat = (object) array('routine' => $routines, 'trainingprogram' => $trainingprograms, 'eatings' => $eatings);

			$this->set("stat", $stat);
			$this->set("currentroutine", $currentroutine);
			$this->set("program", $currentprogram);
			$this->set("currenteating", TableRegistry::get("Eatingprogram")->formateatingprogram($eatingprogram));
			//$this->set("eatings", $eatingprogram);
			$this->set("user", $p);


			$members = TableRegistry::get("Users")
				->find('all')->where(['Users.trainer' => $id, 'Users.active' => 1]);
			$alldays = array();
			foreach($members as $member) {
				$diarydays = TableRegistry::get("Diary")->find('all', [ 'conditions' => [ 'users_id' => $member->id ] ])->toArray();
				array_push($alldays, $diarydays);
			}
			$this->set("days", $alldays);			

			
			/*$this->Flash->success("Ваш тренер создал вам программу тренировок. <a href='/redesign/trainingprogram'>Посмотреть</a>", ['escape' => false]);*/
			//return $this->redirect(['action' => 'view'.'/'.$p->id]);
		} else {
			$this->set("profile", false);
			//return $this->redirect(['action' => 'create']);

		}
    }

    /************************************************************
    Вступление в группу.
    ************************************************************/
    public function subscribe() {
    	$this->autoRender = false;
    	$id = $this->Auth->user('id');
    	$uid = $this->request->data["id"];
    	$usergroups = TableRegistry::get("Usergroup");
    	$usergroup = $usergroups->newEntity();
    	$usergroup->owner = $uid;
    	$usergroup->member = $id;
    	$usergroups->save($usergroup);
    	$usernotification = TableRegistry::get("Usernotification")->find("all", ['conditions' => ['sender' => $uid, 'uid' => $id, 'status' => false, 'messagetype' => 1]])->first();
    	$usernotification->status = true;
    	TableRegistry::get("Usernotification")->save($usernotification);
    	$user = TableRegistry::get("Users")->get($uid);
    	echo "{\"status\" : \"success\", \"corpuser\" : \"" . $user->username . "\"}";
    }

    /************************************************************
    Принятие приглашения от тренера.
    ************************************************************/
    public function confirmtrainer() {
    	$this->autoRender = false;
    	$id = $this->Auth->user('id');
    	$uid = $this->request->data["id"];
    	$users = TableRegistry::get("Users");
    	$user = $users->get($id);
    	//$user = $this->Auth->user();
    	$user->trainer = $uid;
    	$users->save($user);
    	$usernotification = TableRegistry::get("Usernotification")->find("all", ['conditions' => ['sender' => $uid, 'uid' => $id, 'status' => false, 'messagetype' => 2]])->first();
    	$usernotification->status = true;
    	TableRegistry::get("Usernotification")->save($usernotification);
    	$user = TableRegistry::get("Users")->get($uid);
    	echo "{\"status\" : \"success\", \"corpuser\" : \"" . $user->username . "\"}";
    }


    /************************************************************
    Отмена приглашения в группу.
    ************************************************************/
    public function reject() {
    	$this->autoRender = false;
    	$id = $this->Auth->user('id');
    	$uid = $this->request->data["id"];
    	$usernotification = TableRegistry::get("Usernotification")->find("all", ['conditions' => ['sender' => $uid, 'uid' => $id, 'status' => false, 'messagetype' => 1]])->first();
    	$usernotification->status = true;
    	TableRegistry::get("Usernotification")->save($usernotification);
    	echo "{\"status\" : \"success\"}";
    }

    /************************************************************
    Выход из группы.
    ************************************************************/
    public function unsubscribe() {
    	$this->autoRender = false;
    	$id = $this->Auth->user('id');
    	$usergroups = TableRegistry::get("Usergroup");
    	$usergroup = $usergroups->find('all', [ 'conditions' => ['member' => $id]])->first();
    	$usergroups->delete($usergroup);
    	echo "{\"status\" : \"success\"}";
    }

	/*public function view($id) {

		$uid = $this->Auth->user('id');
		$routines = TableRegistry::get("Routine")->find('all', [ 'conditions' => [ 'userId' => $uid ] ])->count();
		$currentroutine = null;
		if (TableRegistry::get("Routine")->find('all', [ "contain" => ["Eating"], 'conditions' => [ 'Routine.userId' => $uid, 'Routine.active' => 1 ] ])->count() > 0)
		$currentroutine = TableRegistry::get("Routine")->find('all', [ "contain" => ["Eating"], 'conditions' => [ 'Routine.userId' => $uid, 'Routine.active' => 1 ] ])->first();
		$trainingprograms = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $uid ] ])->count();
		$currentprogram = null;
		if (TableRegistry::get("Trainingprogram")
			->find('all', ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $uid, "active" => 1 ] ])->count() > 0)
		$currentprogram = TableRegistry::get("Trainingprogram")
			->find('all', ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $uid, "active" => 1 ] ])->first();
		$eatings = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine'], 'conditions' => [ 'userId' => $uid ] ])->count();
		$eatingprogram = null;
		if ($currentroutine!=null && TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'userId' => $uid, 'routine_id' => $currentroutine->id, "Eatingprogram.active" => 1] ])->count() > 0)
		$eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'userId' => $uid, 'routine_id' => $currentroutine->id, "Eatingprogram.active" => 1] ])->first();

		
		$stat = (object) array('routine' => $routines, 'trainingprogram' => $trainingprograms, 'eatings' => $eatings);

		$profile = TableRegistry::get("Profiles")->get($id);

		$profile->age = $this->getFullYears($profile->birthday);

		$this->set("stat", $stat);
		$this->set("model", $profile);
		$this->set("currentroutine", $currentroutine);
		$this->set("program", $currentprogram);
		$this->set("eatings", $eatingprogram);
	}*/

	/************************************************************
	Вычисление количества полных лет.
	************************************************************/
	private function getFullYears($birthdayDate) {
        $tz = new \DateTimeZone('Europe/Moscow');
        $interval = $birthdayDate->diff(new \DateTime('now', $tz));
        return $interval->format("%Y");
	}

	/************************************************************
	Заполнение профиля для нового пользователя.
	************************************************************/
	public function create() {
		if ($this->request->is("post")) {
			$this->autoRender = false;

			$id = $this->Auth->user('id');

			//var_dump($this->request->data['avatar']);
			if ($this->Auth->user("role") == "trainer") {
				$profiles = TableRegistry::get("Trainerprofile");
			$profile = $profiles->newEntity();
			$profile = $profiles->patchEntity($profile, $this->request->data);
			$profile->users_id = $id;
			$profile->active = true;
			$b = $this->request->data["birthday"];
			$brithdate = explode('.', $b);
			$brithdateFormated = $brithdate[2] . "-" . $brithdate[1] . "-" . $brithdate[0];
			$profile->birthday = $brithdateFormated;
			//upload file
			
			if (isset($_FILES["avatar"])) {
				$target_path = WWW_ROOT . 'img/excersices/';
				$file_name = $this->request->data['avatar']['name'];
	            $parts = explode(".", $file_name);
	            $fname=time().uniqid(rand());
	            list($width, $height, $typeCode) = getimagesize($_FILES["avatar"]["tmp_name"]);
				$imageType = ($typeCode == 1 ? "gif" : ($typeCode == 2 ? "jpeg" : ($typeCode == 3 ? "png" : FALSE)));

	            
	            $to_path = $target_path . $fname . "." . $imageType; //set the target path with a new name of image
	            $profile->avatar = $fname . "." . $imageType;
	            
	            if ($file_name != '') {

	                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $to_path)) {

	                }
	            }
        	}

			$validator = $profiles->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				$this->set("profile", $profile);
				
				echo "{\"status\" : \"error\"}";
				//$this->Flash->error($errors);
			}
				//echo($errors);
			else {

				if ($profiles->save($profile)) {
					// Синхронизируем личные данные тренера в таблицу profiles
					$physTable = TableRegistry::get("Profiles");
					$phys = $physTable->find()->where(["userId" => $id])->first();
					if (!$phys) {
						$phys = $physTable->newEntity();
						$phys->userId = $id;
						$phys->active = 1;
					}
					$phys->name       = isset($this->request->data["name"])       ? $this->request->data["name"]       : "";
					$phys->fam        = isset($this->request->data["fam"])        ? $this->request->data["fam"]        : null;
					$phys->otch       = isset($this->request->data["otch"])       ? $this->request->data["otch"]       : null;
					$phys->sex        = isset($this->request->data["sex"])        ? $this->request->data["sex"]        : null;
					$phys->birthday   = isset($brithdateFormated)                 ? $brithdateFormated                 : null;
					$phys->growth     = isset($this->request->data["growth"])     ? $this->request->data["growth"]     : null;
					$phys->weight     = isset($this->request->data["weight"])     ? $this->request->data["weight"]     : null;
					$phys->aimTrain   = isset($this->request->data["aimTrain"])   ? $this->request->data["aimTrain"]   : null;
					$phys->somatotype = isset($this->request->data["somatotype"]) ? $this->request->data["somatotype"] : null;
					$physTable->save($phys);

					$this->autoRender = false;
					echo "{\"status\" : \"success\"}";
					//$this->Flash->Success('Профиль успешно создан');
					//return $this->redirect(['action' => 'view'.'/'.$profile->id]);	
				}
			}
			} else {
			$profiles = TableRegistry::get("Profiles");
			$profile = $profiles->newEntity();
			$profile = $profiles->patchEntity($profile, $this->request->data);
			$profile->userId = $id;
			$profile->active = true;
			$b = $this->request->data["birthday"];
			$brithdate = explode('.', $b);
			$brithdateFormated = $brithdate[2] . "-" . $brithdate[1] . "-" . $brithdate[0];
			$profile->birthday = $brithdateFormated;
			//upload file
			
			if (isset($_FILES["avatar"])) {
				$target_path = WWW_ROOT . 'img/excersices/';
				$file_name = $this->request->data['avatar']['name'];
	            $parts = explode(".", $file_name);
	            $fname=time().uniqid(rand());
	            list($width, $height, $typeCode) = getimagesize($_FILES["avatar"]["tmp_name"]);
				$imageType = ($typeCode == 1 ? "gif" : ($typeCode == 2 ? "jpeg" : ($typeCode == 3 ? "png" : FALSE)));

	            
	            $to_path = $target_path . $fname . "." . $imageType; //set the target path with a new name of image
	            $profile->avatar = $fname . "." . $imageType;
	            
	            if ($file_name != '') {

	                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $to_path)) {

	                }
	            }
        	}

			$validator = $profiles->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				$this->set("profile", $profile);
				
				echo json_encode(["status" => "error", "errors" => $errors]);
				//$this->Flash->error($errors);
			}
				//echo($errors);
			else {

				if ($profiles->save($profile)) {

					$this->autoRender = false;
					echo "{\"status\" : \"success\"}";
					//$this->Flash->Success('Профиль успешно создан');
					//return $this->redirect(['action' => 'view'.'/'.$profile->id]);	
				}
			}
		}
		}
		$this->viewBuilder()->layout('create_profile');
		$this->set("mode", "create");
		if ($this->Auth->user("role") == "trainer")
			$this->viewBuilder()->template("trainercreate");
	}
	
	/************************************************************
	Изменение профиля для существующего пользователя.
	************************************************************/
	public function edit($id) {

		// Определяем таблицу по ID: если тренер и запись есть в trainerprofile — используем её
		$isTrainerProfile = false;
		$profiles = TableRegistry::get("Profiles");
		if ($this->Auth->user("role") == "trainer") {
			$trainerTable = TableRegistry::get("Trainerprofile");
			$check = $trainerTable->find()->where(["id" => $id, "users_id" => $this->Auth->user("id")])->first();
			if ($check !== null) {
				$profiles = $trainerTable;
				$isTrainerProfile = true;
			}
		}
		$profile = $profiles->get($id);

		if ($this->request->is("post")) {
			$this->autoRender = false;

			//var_dump($this->request->data['avatar']);
			
			$profile = $profiles->patchEntity($profile, $this->request->data);
			$b = $this->request->data["birthday"];
			$brithdate = explode('.', $b);
			$brithdateFormated = $brithdate[2] . "-" . $brithdate[1] . "-" . $brithdate[0];
			$profile->birthday = $brithdateFormated;
			$validator = $profiles->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors))
				echo($errors);
			else 

			if ($profiles->save($profile)) {

				// Если обновляли trainerprofile — синхронизируем личные данные в таблицу profiles
				if ($isTrainerProfile) {
					$physTable = TableRegistry::get("Profiles");
					$phys = $physTable->find()->where(["userId" => $this->Auth->user("id")])->first();
					if (!$phys) {
						$phys = $physTable->newEntity();
						$phys->userId = $this->Auth->user("id");
						$phys->active = 1;
					}
					$phys->name       = isset($this->request->data["name"])       ? $this->request->data["name"]       : "";
					$phys->fam        = isset($this->request->data["fam"])        ? $this->request->data["fam"]        : null;
					$phys->otch       = isset($this->request->data["otch"])       ? $this->request->data["otch"]       : null;
					$phys->sex        = isset($this->request->data["sex"])        ? $this->request->data["sex"]        : null;
					$phys->birthday   = isset($brithdateFormated)                 ? $brithdateFormated                 : null;
					$phys->growth     = isset($this->request->data["growth"])     ? $this->request->data["growth"]     : null;
					$phys->weight     = isset($this->request->data["weight"])     ? $this->request->data["weight"]     : null;
					$phys->aimTrain   = isset($this->request->data["aimTrain"])   ? $this->request->data["aimTrain"]   : null;
					$phys->somatotype = isset($this->request->data["somatotype"]) ? $this->request->data["somatotype"] : null;
					$physTable->save($phys);
				}

				//$this->Flash->Success('Профиль успешно сохранен');
				echo ("{\"status\" : \"success\"}");
				/*return $this->redirect(['action' => 'index', 'prefix' => 'Redesign']);	*/
			}
		} else {
 
		$this->set("user", $profile);
		$this->set("mode", "edit");
		if ($isTrainerProfile) {
			$this->render('trainercreate');
		} else {
			$this->render('create');
		}
		}
	}

	/************************************************************
	Изменение аватара пользователя.
	************************************************************/
	public function updateavatar($id) {
		$profiles = TableRegistry::get("Profiles");
		$profile = $profiles->get($id);		

		if ($this->request->is("post")) {
			$this->autoRender = false;
			if (isset($_FILES["avatar"])) {
				$target_path = WWW_ROOT . 'img/excersices/';
				$file_name = $this->request->data['avatar']['name'];
	            $parts = explode(".", $file_name);
	            $fname=time().uniqid(rand());
	            list($width, $height, $typeCode) = getimagesize($_FILES["avatar"]["tmp_name"]);
				$imageType = ($typeCode == 1 ? "gif" : ($typeCode == 2 ? "jpeg" : ($typeCode == 3 ? "png" : FALSE)));

	            
	            $to_path = $target_path . $fname . "." . $imageType; //set the target path with a new name of image
	            $profile->avatar = $fname . "." . $imageType;
	            
	            if ($file_name != '') {

	                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $to_path)) {

	                }
	            }
        	}
        	$validator = $profiles->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				echo "{\"status\" : \"error\"}";
			}
			else {
				if ($profiles->save($profile)) {
					echo "{\"status\" : \"success\", \"url\" : \"/img/excersices/" . $profile->avatar . "\"}";
					//$this->Flash->Success('Профиль успешно создан');
					//return $this->redirect(['action' => 'view'.'/'.$profile->id]);	
				}
			}
		} else {
			echo "{\"status\" : \"error\"}";
		}
	}

	/************************************************************
	Удаление профиля пользователя.  !!!Не используется
	************************************************************/
	public function delete($id) {

		$this->autoRender = false;

		$profiles = TableRegistry::get("Profiles");
		$profile = $profiles->get($id);		
		$profiles->delete($profile);

		return $this->redirect(['action' => 'index']);
	}

	/************************************************************
	Активация профиля.   !!!Не используется. Профиль активируется по дефолиу.
	************************************************************/
	public function active() {

		$this->autoRender = false;

		if ($this->request->is('post')) {
			$id = $this->Auth->user('id');
			$r = $this->request->input();
			//var_dump($r);
			$data = json_decode($this->request->input());
			//var_dump($data);
			$profiles = TableRegistry::get("Profiles");
			//var_dump($this->request->input());
			$profile = $profiles->get($data->profile);
			TableRegistry::get("Profiles")->updateAll(['active' => 0], ['userId' => $id]);
			$profile->active = true;
			$profiles->save($profile);

			echo $this->ok();
		}
	}


	
}