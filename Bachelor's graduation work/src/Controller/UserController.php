<? 
namespace App\Controller;


use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validator;

class UserController extends AppController
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
        
        $this->set("section", "user");
        $this->set("userrole", $this->Auth->user("role"));
        if ($this->Auth->user("role") == "admin") {
        	$this->viewBuilder()->layout('admin');
        	$this->set("admin", true);
        }

        else 
        	$this->viewBuilder()->layout('corpuser');
    }



    public function isAuthorized($user) {
        
        return true;
    }

    public function index() {

    	//$users = TableRegistry::get("Users")->find('all', []);
		
		if (isset($_GET['useradded'])) {
			$this->Flash->success("Запрос на добавление пользователей успешно отправлен");
		}
		$uid = $this->Auth->user('id');
		$this->set("userrole", $this->Auth->user('role'));
		$users = null;
		if ($this->Auth->user('role') == "corp" ) {
		$users = TableRegistry::get("Users")
				->find('all', ['contain' => ['usergroup' => ["member"]], 'conditions' => [ 'id' => $uid]]);
		$alltrainers = [];
		foreach ($users->toArray()[0]->usergroup as $key => $user) {
			if ($user->member['role'] == 'trainer')
				$alltrainers[] = $user->member;
		}
		$this->set("trainers", $alltrainers);
		} else {
			$users = TableRegistry::get("Users")
				->find('all', ['contain' => ['Profiles']])->where(['Users.trainer' => $uid, 'Users.active' => 1]);
		}
		$this->set("users", $users);

    }

    public function settrainer() {
    	$this->autoRender = false;
    	$trainid = $_POST['trainid'];
    	$userid = $_POST["userid"];
    	$users = TableRegistry::get("Users");
    	$user = $users->get($userid);
    	$user->trainer = $trainid;
    	$users->save($user);
    	echo "{\"status\": \"success\"}";
    }

    public function unsettrainer() {
    	$this->autoRender = false;
    	$id = $_POST['id'];
    	//$id = $this->Auth->user('id');
    	$users = TableRegistry::get("Users");
    	$user = $users->get($id);
    	$user->trainer = null;
    	var_dump($user);
    	$users->save($user);
    	echo "{\"status\": \"success\"}";	
    }

    public function userinfo($uid) {
    	$id = $this->Auth->user('id');
    	$users = null;
		if ($this->Auth->user('role') == "corp") {
		$users = TableRegistry::get("Users")
				->find('all', ['contain' => ['usergroup' => ["member"]], 'conditions' => [ 'id' => $id]]);
		$alltrainers = [];
		foreach ($users->toArray()[0]->usergroup as $key => $user) {
			if ($user->member['role'] == 'trainer')
				$alltrainers[] = $user->member;
		}
		$this->set("trainers", $alltrainers);
		} else {
			$users = TableRegistry::get("Users")
				->find('all', ['conditions' => [ 'trainer' => $id]]);
		}
		$this->set("users", $users);
		$this->set("activeuser", $uid);
		$this->render('index');
    }

    public function invite() {
    	if ($this->Auth->user('role') == "corp")
    	{
    		$users = TableRegistry::get("Users")->find('all', []);
    		$this->set("users", $users);
    	} else if($this->Auth->user('role') == "trainer") {
    		$users = TableRegistry::get("Users")->find('all', ['contain' => ['Profiles', 'Trainerprofile']]);
    		$this->set("users", $users);
    		$usernotices = TableRegistry::get("usernotification")->find('all', ["conditions" => ["sender" => $this->Auth->user('id'), "status" => 0, "messagetype" => 2]]);
    		$this->set("usernotices", $usernotices);
    	}

    }

    public function uninviteuser() {
    	try{
    		$this->autoRender = false;
    		$id = $this->Auth->user('id');
    		$member = $this->request->data["users"];
    		$usernotises = TableRegistry::get("usernotification");
    		$delnotise = $usernotises->find('all', ['conditions' => ['sender' => $id, 'uid' => $member]])->first();
    		$usernotises->delete($delnotise);
    		echo "{\"status\" : \"success\"}"; 
    	} catch(Exception $e) {
    		echo "{\"status\" : \"error\"}"; 
    	}
    }

    private function getFullYears($birthdayDate) {
        //Log::write('debug', $birthdayDate);
        $datetime = new \DateTime($birthdayDate);
        //Log::write('debug', $datetime);
        $interval = $datetime->diff(new \DateTime(date("Y-m-d")));
        return $interval->format("%Y");
	}

    public function getuserinfo() {
		$this->autoRender = false;
		// Поддержка обоих способов получения POST-данных в CakePHP 3
		$id = isset($this->request->data["id"]) ? $this->request->data["id"] : $this->request->data("id");
		if (empty($id)) {
			echo json_encode(["status" => "error", "message" => "id not received"]);
			return;
		}
		$id = (int)$id;
		$user = TableRegistry::get("Users")->get($id);
		$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true] ]);
		if ($profiles->count() > 0) {
			$p = $profiles->first();
			$p->age = $this->getFullYears($p->birthday);
			$p->username = $user->username;
			$p->role = $user->role;
			$p->userid = $user->id;
			
			$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id ]] )->count();
			$currentroutine = null;
			if ($routines > 0)
			$currentroutine = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'Routine.userId' => $id, 'Routine.active' => 1 ] ])->first();
			$trainingprograms = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $id ] ])->count();
			$currentprogram = null;
			if (TableRegistry::get("Trainingprogram")
				->find('all', ['contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'Trainingprogram.users_id' => $id, 'Trainingprogram.active' => 1 ] ])->count() > 0)
			$currentprogram = TableRegistry::get("Trainingprogram")
				->find('all', ['contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'Trainingprogram.users_id' => $id, 'Trainingprogram.active' => 1 ] ])->first();
			$eatings = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine'], 'conditions' => [ 'users_id' => $id ] ])->count();
			$eatingprogram = null;
			if ($currentroutine!=null && TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $id, 'routine_id' => $currentroutine->id, "Eatingprogram.active" => 1] ])->count() > 0)
			$eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $id, 'routine_id' => $currentroutine->id, "Eatingprogram.active" => 1] ])->first();

			
			$stat = (object) array('routine' => $routines, 'trainingprogram' => $trainingprograms, 'eatings' => $eatings);

			$eatingFormatted = $eatingprogram ? $this->formateatingprogram($eatingprogram) : null;
			$data = "{\"status\": \"success\", \"profile\" : " . json_encode($p) . ", \"stat\" : " . json_encode($stat) . ", \"currentroutine\" : " . json_encode($currentroutine) . ", \"program\" : " . json_encode($currentprogram) . ", \"eating\" : " . json_encode($eatingFormatted) . "}";

			/*$this->set("stat", $stat);
			$this->set("currentroutine", $currentroutine);
			$this->set("program", $currentprogram);
			$this->set("eatings", $eatingprogram);
			$this->set("user", $p);*/
			//print_r($this->request->data["id"]);
			echo $data;
			//return $this->redirect(['action' => 'view'.'/'.$p->id]);
		}  else {
			echo "{\"status\" : \"error\", \"message\" : \"Профиль не создан\"}";
		}
    } 

    public function inviteusers() {
    	$this->autoRender = false;
    	$id = $this->Auth->user('id');
    	//print_r ($this->request->data);
    	$members = $this->request->data["users"];
    	//echo $members;
    	
    	$usernotises = TableRegistry::get("usernotification");
    	foreach($members as $member) {
			$notice = $usernotises->newEntity();
			$notice->uid = $member;
			$notice->sender = $id;
			if ($this->Auth->user("role") == "trainer")
				$notice->messagetype = 2;	
			else
				$notice->messagetype = 1;
			$usernotises->save($notice);
    	}
    	echo "{\"status\" : \"success\"}";
    }

    public function getuserprofile() {
		$this->autoRender = false;
		$id = $this->request->data["id"];
		$user = TableRegistry::get("Users")->get($id);
		$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true] ]);
		if ($profiles->count() > 0) {
			$p = $profiles->first();
			$p->age = $this->getFullYears($p->birthday);
			$p->username = $user->username;
			$p->role = $user->role;
			$p->userid = $user->id;
			
			$data = "{\"status\": \"success\", \"profile\" : " . json_encode($p) . "}";
			echo $data;
		}  else {
			echo "{\"status\" : \"error\", \"message\" : \"Профиль не создан\"}";
		}
    } 

    public function updateprofile($uid, $id) {
		$profiles = TableRegistry::get("Profiles");
		$profile = $profiles->get($id);
		$this->set("assign", true);
		$user = TableRegistry::get("Users")->get($uid);
		$this->set("user1", $user);
		$this->set("user", $profile);
		$this->set("mode", "edit");
    }

    public function saveprofile() {
    	if ($this->request->is("post")) {
			$this->autoRender = false;
			$profiles = TableRegistry::get("Profiles");
			$profile = $profiles->get($_POST['profileid']);	
			
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

				//$this->Flash->Success('Профиль успешно сохранен');
				echo ("{\"status\" : \"success\"}");
				/*return $this->redirect(['action' => 'index', 'prefix' => 'Redesign']);	*/
			}
		}
    }

    public function groupdelete($id) {
   		$this->autoRender = false;
   		$usergroups = TableRegistry::get("Usergroup");
    	$usergroup = $usergroups->find('all', [ 'conditions' => ['member' => $id]])->first();
    	$usergroups->delete($usergroup);
    	return $this->redirect(['action' => 'index']);
    }

    public function delete($id) {

		$this->autoRender = false;

		$users = TableRegistry::get("Users");
		$user = $users->get($id);
		if ($user["role"] == "admin") {
			$this->Flash->error("Нельзя удалять администратора");

		} else 

			$users->delete($user);

		return $this->redirect(['action' => 'index']);
	}

	public function viewuserroutine($uid) {
		$id = $this->Auth->user('id');
		$user = TableRegistry::get("Users")->get($uid);
		$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $uid ]] );
		$templates = TableRegistry::get("Routine")->find('all', ['conditions' => [ 'userId' => $id ]] );
		$this->set("user", $user);
		$this->set("assign", true);
		$this->set("templates", $templates);
		$this->set("routines", $this->paginate($routines));
	}

	public function saveuserroutine() {
		if ($_POST) {

			//var_dump($_POST);

			$id = $_POST["userid"];
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

				return $this->redirect("/user/viewuserroutine/".$id);
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

	public function createuserroutinebytmp($uid, $routineid) {
		$user = TableRegistry::get("Users")->get($uid);
		$routine = TableRegistry::get("Routine")->get($routineid, ['contain' => ['Routineday'=>['Eating']]]);
		$this->set("assign", true);
		$this->set("routine", $routine);
		$this->set("user", $user);
		$this->render('createuserroutine');
	}

	public function createuserroutine($uid) {
		$this->set("assign", true);
		$user = TableRegistry::get("Users")->get($uid);
		$this->set("user", $user);
		$this->render('createuserroutine');
	}

	public function usertrainingprogram($id) {
		$user = TableRegistry::get("Users")->get($id);
		$trainingprograms = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $id ] ]);
		$this->set("programs", $trainingprograms->all());

		$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true] ]);
		$this->set("aimTrain", $profiles->count()>0?$profiles->first()->aimTrain:null);

		$this->set("user", $user);

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
        $this->set("programsinfo", $trainingprograminfo->all());
        $this->set("assign", true);

        $curid = $this->Auth->user('id');
        $currentprogs = TableRegistry::get("Trainingprogram")
			->find('all', ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $curid ]]);
        $this->set("templates", $currentprogs);

	}

	private function formateatingprogram($program) {
        $obj = new \stdClass;
            $obj->id = $program->id;
            $obj->name = $program->name;
            $obj->active = $program->active;
            $obj->routine_id = $program->routine_id;
            $obj->routine_name = $program->routine->name;
            $obj->lastmodified = $program->lastmodified ? $program->lastmodified->format('Y-m-d H:i:s') : null;
            $obj->date_active = $program->date_active ? $program->date_active->format('Y-m-d H:i:s') : null;
            $key2 = [];
            $key2[$program->routine->routineday[0]->id] = 0;
            $key2[$program->routine->routineday[1]->id] = 1;
            $key3 = [];
            for ($ii=0; $ii < 2; $ii++)
                foreach ($program->routine->routineday[$ii]->eating as $key => $value) 
                    $key3[$value->id] = $program->routine->routineday[$ii]->id;        
                

            $days=[];
            foreach ($program->routineeatingmenu as $key => $menu) {
                if (!array_key_exists($menu->day_number, $days)) {
                    //for ($ii=0; $ii < 2; $ii++)
                    $ii = $key2[$key3[$menu->eating_id]];
                    foreach ($program->routine->routineday[$ii]->eating as $key => $value) {
                        //$days[$menu->day_number][(int)$value->id] = (object)['foods' => [], 'eating' => $value, "number" => $key];
                        $days[$menu->day_number][$key] = (object)['foods' => [], 'eating' => $value, "number" => $key];
                        $keys[$value->id] = $key;
                    }
                }
                //$days[$menu->day_number][(int)$menu->eating_id]->foods[count($days[$menu->day_number][(int)$menu->eating_id]->foods)] = $menu;
                $menu->food->colories = $menu->cnt/100 * $menu->food->colories;
                $menu->food->hidrocarbonats = $menu->cnt/100 * $menu->food->hidrocarbonats;
                $menu->food->fats = $menu->cnt/100 * $menu->food->fats;
                $menu->food->proteins = $menu->cnt/100 * $menu->food->proteins;
                $days[$menu->day_number][$keys[$menu->eating_id]]->foods[count($days[$menu->day_number][$keys[$menu->eating_id]]->foods)] = $menu;
            }
            $obj->days = $days;
            //$eatings[$program->id] = $obj;
            return $obj;
    }

	function usernutritionprogram($id) {
		$user = TableRegistry::get("Users")->get($id);
		$uid = $this->Auth->user('id');
    	$eatingprograms = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $id] ]);
    	$eatings = [];
    	$keys = [];
        
    	foreach ($eatingprograms as $key => $program) {
    		$eatings[$program->id] = $this->formateatingprogram($program);
    	}
    	$activeroutine = TableRegistry::get("Routine")->find("all", ["conditions" => ["userId" => $id, "active" => true]]);
    	$this->set("activeroutineid", $activeroutine->first()->id);
    	$this->set("eatingprograms", $eatings);
    	$this->set("title", "Программа питания");
    	$this->set("assign", true);
    	$this->set("user", $user);
    	$templates = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $uid] ]);
        $this->set("templates", $templates);
	}

	function createusernutritionprogram($geterid) {	
		$this->set("program", null);
		//$this->set("assign", true);
		$user = TableRegistry::get("Users")->get($geterid);

    	$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $geterid, 'active' => true ] ]);
    	$profile = $profiles->first();
    	$this->loadComponent('Diet');

    	$activityCoef = $this->Diet->getActivityCoef($profile);
    	$age = $this->Diet->getAge($profile);
    	$NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
    	$NB += $this->Diet->getAimOffset($profile);
    	$this->Diet->setAveKkal($NB);

    	$bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
        $bgunorm["Kkal"] = $NB;
    	$uid = $this->Auth->user('id');
    	$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
        $ids = [$uid, $admin->first()->id];
        $foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
        $this->set("foodcategories", $foodcategories);
    	//$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Eating'], 'conditions' => [ 'userId' => $id ] ]);
    	$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $geterid ]] );
    	$this->set("routines", $routines);
    	$this->set("bgunorm", $bgunorm);
    	$this->set("test", "test");



		$this->set("user", $user);
		$this->set("mode", "create");
		//$this->set("assign", true);
		$this->set("redirect", "/user/saveeatingprogram");		
	}

	function createusernutritionprogramtmp($geterid, $tpid) {	
		$this->set("program", null);
		//$this->set("assign", true);
		$user = TableRegistry::get("Users")->get($geterid);

    	$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $geterid, 'active' => true ] ]);
    	$profile = $profiles->first();
    	$this->loadComponent('Diet');

    	$activityCoef = $this->Diet->getActivityCoef($profile);
    	$age = $this->Diet->getAge($profile);
    	$NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
    	$NB += $this->Diet->getAimOffset($profile);
    	$this->Diet->setAveKkal($NB);

    	$bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
        $bgunorm["Kkal"] = $NB;
    	$uid = $this->Auth->user('id');
    	$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
        $ids = [$uid, $admin->first()->id];
        $foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
        $this->set("foodcategories", $foodcategories);
    	//$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Eating'], 'conditions' => [ 'userId' => $id ] ]);
    	$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $geterid ]] );
    	$uid = $this->Auth->user('id');
    	$program = TableRegistry::get("Eatingprogram")->get($tpid, ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $uid] ]);
    	$equalroutines = $this->checkroutine($program->routine_id, $geterid);
    	if (count($equalroutines) == 0) {
    		$this->Flash->error("У пользователя для которого создаётся программа питания нет ни одного распорядка дня соответствующего распорядку дня в шаблоне.");
            //return $this->redirect('/admin/nutritionprogram');
            return $this->redirect('user/usernutritionprogram/'.$geterid);
    	}
    	$this->set("equal", $equalroutines);
    	$program->routine_id = $equalroutines[0]->id;

        $obj = $this->formateatingprogram($program);
    	$this->set("routines", $routines);
    	$this->set("bgunorm", /*"{'NB': $NB, 'CMA': $CMA}"*/$bgunorm);
    	$this->set("test", "test");
    	$this->set("program", $obj);



		$this->set("user", $user);
		$this->set("mode", "create");
		//$this->set("assign", true);
		$this->set("redirect", "/user/saveeatingprogram");	
		$this->render("createusernutritionprogram");	
	}

	function checkroutine($rid, $id) {
		$routine = TableRegistry::get("Routine")->get($rid, ['contain' => ['Routineday'=>['Eating']]]);
		$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id ]] );
		$equalroutines = [];
		foreach($routines as $key => $value) {
			if (count($value->routineday)==2 && count($value->routineday[0]->eating) == count($routine->routineday[0]->eating) && count($value->routineday[1]->eating) == count($routine->routineday[1]->eating))
				$equalroutines[count($equalroutines)] = $value;
		}
		return $equalroutines;
	}

	function editusernutritionprogram($id, $programid) {
		$user = TableRegistry::get("Users")->get($id);
    	$program = TableRegistry::get("Eatingprogram")->get($programid, ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $id] ]);
    	$keys = [];
        $obj = $this->formateatingprogram($program);



    	//$uid = $this->Auth->user('id');
    	$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true ] ]);
    	$profile = $profiles->first();
    	$this->loadComponent('Diet');

    	$activityCoef = $this->Diet->getActivityCoef($profile);
    	$age = $this->Diet->getAge($profile);
    	$NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
    	$NB += $this->Diet->getAimOffset($profile);
    	$this->Diet->setAveKkal($NB);

    	$bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
        $bgunorm["Kkal"] = $NB;
    	$uid = $this->Auth->user('id');
    	$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
        $ids = [$uid, $admin->first()->id];
        $foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
        $this->set("foodcategories", $foodcategories);
    	//$id = $this->Auth->user('id');
    	//$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Eating'], 'conditions' => [ 'userId' => $id ] ]);

    	$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id ]] );
    	$this->set("routines", $routines);
    	$this->set("bgunorm", /*"{'NB': $NB, 'CMA': $CMA}"*/$bgunorm);
		$this->set("program", $obj);
		$this->set("user", $user);
		$this->set("mode", "edit");
		$this->set("redirect", "/user/editeatingprogram");
		$this->render('createusernutritionprogram');
	}

	function createusertrainingprogramtmp($geterid, $tpid) {
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
		//$this->set("assign", true);
		$this->set("mode", "create");
		$this->set("redirect", "/user/saveprogram");
		$user = TableRegistry::get("Users")->get($geterid);
		$this->set("user", $user);
		$this->render("createusertrainingprogram");
	}

	function createusertrainingprogram($geterid) {	
		$this->set("program", null);
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];

		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("musculgroups", $musculgroups);
		//$this->set("assign", true);
		$user = TableRegistry::get("Users")->get($geterid);
		$this->set("user", $user);
		$this->set("mode", "create");
		//$this->set("assign", true);
		$this->set("redirect", "/user/saveprogram");	
	}

	function editusertrainingprogram($geterid, $tpid) {
		$uid = $this->Auth->user('id');

		$trainingprogram = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $geterid, "id" => $tpid ], 'contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise']]] ])->first();
           if ($trainingprogram == null) 
           	throw new NotFoundException();
		$this->set("program", $trainingprogram);
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];

		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("musculgroups", $musculgroups);
		$this->set("redirect", "/user/editprogram");
		$user = TableRegistry::get("Users")->get($geterid);
		$this->set("user", $user);
		$this->set("mode", "edit");
		$this->render('createusertrainingprogram');
	}

	private function validateInput() {
    	for ($i = 0; $i < count($_POST['exercise']); $i++) 
    		if (count($_POST['exercise'][$i+1]) == 0)
    			return false;
    	return true;
    }

    function editprogram() {
    	if ($_POST) {
	    	$id = $_POST['user'];
	    	$tpid = $_POST['program'];
	    	$trainingprogram = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $id, "id" => $tpid ], 'contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise']]] ])->first();
	           if ($trainingprogram == null) 
	           	throw new NotFoundException();
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
					$trainingdays->deleteAll([ 'trainingprogram_id' => $trainingprogram->id ]);				

					for ($i = 0; $i < count($_POST['exercise']); $i++) {

						//$eatTime = date("H:i", mktime($_POST['eatTime'][$i]['hour'], $_POST['eatTime'][$i]['minute']));
						$trainingday = $trainingdays->newEntity();
						$trainingday->number = $i+1;
						$trainingday->trainingprogram_id = $trainingprogram->id;
						$trainingdays->save($trainingday);
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

					return $this->redirect("/user/usertrainingprogram/".$trainingprogram->userId);
				}
				else {$this->set("error", "empty day");}
					return $this->redirect("/user/editusertrainingprogram/".$id."/".$tpid);
				}
			}
		else {
			return $this->redirect("/user/editusertrainingprogram/".$id."/".$tpid);
		}	
    }

	function saveprogram() {

		if ($_POST) {

			//$id = $this->Auth->user('id');
			$id = $_POST['user'];
			
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
						$trainingdayexs->save($trainingdayex);
						//$trainingdayex->
					}
				}

				return $this->redirect("/user/usertrainingprogram/".$id);
			}
			else {
				$this->set("error", "empty day");
			}		
		}
		$this->render('create');
	}

	function saveeatingprogram() {
		if ($_POST) {

			$id = $_POST['user'];
			$programs = TableRegistry::get("Eatingprogram");
			$program = $programs->newEntity();
			$program->name = trim($_POST['name']);
			$program->routine_id = $_POST['routine'];
            $program->users_id = $id;
            $program->lastmodified = (new \DateTime())->format("Y-m-d H:i:s");
			$validator = $programs->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			
			$eatings = TableRegistry::get("routineeatingmenu");

			if (empty($errors) && $programs->save($program)) {
					foreach($_POST['foods'] AS $daynum => $v){
						//print_r($v);
						foreach($v AS $eatingid => $foods) {
							//echo $eatingid;
							//print_r ($foods);
							foreach ($foods as $key => $value) {
								$eating = $eatings->newEntity();
								$eating->eatingprogram_id = $program->id;
								$eating->eating_id = $eatingid;
								//echo($eating->eating_id);
								$eating->food_id = $value[0];
								$eating->day_number = $daynum;
								$eating->cnt = $value[1];
								$eatings->save($eating);
							}
						}
					}
			}
			return $this->redirect("/user/usernutritionprogram/".$id);
		}
	}

	public function editeatingprogram() {
		if ($_POST) {

            $uid = $_POST['user'];
            $tpid = $_POST['program'];
            $programs = TableRegistry::get("Eatingprogram");
            $program = $programs->get($tpid); 
            $eatingmenus = TableRegistry::get("Routineeatingmenu");
            

            $eatingmenus->deleteAll(['eatingprogram_id' => $program->id]);
            /*if ($eatingprogram->routine_id != $_POST['routine']) {
                $programs.delete($eatingprogram);
            }*/

            $program->name = trim($_POST['name']);
            
            $program->routine_id = $_POST['routine'];
            $validator = $programs->validationDefault(new Validator());
            $errors = $validator->errors($this->request->data());
            
            
            $eatings = TableRegistry::get("routineeatingmenu");

            if (empty($errors) && $programs->save($program)) {
                    foreach($_POST['foods'] AS $daynum => $v){
                        foreach($v AS $eatingid => $foods) {
                            //echo $eatingid;
                            //print_r ($foods);
                            foreach ($foods as $key => $value) {
                                $eating = $eatings->newEntity();
                                $eating->eatingprogram_id = $program->id;
                                $eating->eating_id = $eatingid;
                                //echo($eating->eating_id);
                                $eating->food_id = $value[0];
                                $eating->day_number = $daynum;
                                $eating->cnt = $value[1];
                                $eatings->save($eating);
                            }
                        }
                    }
            }
    	   return $this->redirect('/user/usernutritionprogram/' . $uid);
        }
	}

	public function activenutritionprogram($uid, $id) {
			//$r = $this->request->input();
			//$data = json_decode($this->request->input());
			//var_dump($data);
			$programs = TableRegistry::get("Eatingprogram");
			//var_dump($this->request->input());
			$program = $programs->get($id);
			TableRegistry::get("Eatingprogram")->updateAll(['active' => 0], ['routine_id =' => $program->routine_id, "users_id =" => $uid]);
			$program->active = true;
            $program->date_active = (new \DateTime())->format("Y-m-d H:i:s");
			$programs->save($program);
			return $this->redirect('/user/usernutritionprogram/' . $uid);	
	}

	public function deletenutritionprogram($uid, $id) {
		$this->autoRender = false;

		$programs = TableRegistry::get("Eatingprogram");
		$eatingmenus = TableRegistry::get("Routineeatingmenu");
		$program = $programs->get($id);
		$eatingmenus->deleteAll(['eatingprogram_id' => $program->id]);
		$programs->delete($program);
		return $this->redirect('/user/usernutritionprogram/' . $uid);
	}

	public function activetrainingprogram($uid, $id) {
		$programs = TableRegistry::get("Trainingprogram");
		$program = $programs->get($id);
		TableRegistry::get("Trainingprogram")->updateAll(['active' => 0], ['users_id =' => $uid]);
		$program->active = true;
		$programs->save($program);
		return $this->redirect("/user/usertrainingprogram/".$uid);	
	}  

	public function deletetrainingprogram($uid, $id) {

		$this->autoRender = false;

		$programs = TableRegistry::get("Trainingprogram");
		$trainingdays = TableRegistry::get("Trainingprogramday");
		$trainingprogramdaysexercises = TableRegistry::get("Trainingprogramday_Exercise");
		$program = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $uid, "id" => $id ], 'contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise']]] ])->first();
		if ($program == null)
			throw new NotFoundException();
		$trainingday = $trainingdays->find('all', ['conditions'=>['trainingprogram_id' => $program->id]]);
				foreach ($trainingday as $id => $day) {
					$trainingprogramdaysexercises->deleteAll([ 'trainingprogramday_id' => $day->id ]);		
				}
				$trainingdays->deleteAll([ 'trainingprogram_id' => $id ]);

		$programs->delete($program);
		return $this->redirect("/user/usertrainingprogram/".$uid);	
	}

	public function changerole() {
		$this->autoRender = false;
		$id = $this->request->data["id"];
		$user = TableRegistry::get("Users")->get($id);
		$user->role = $this->request->data["role"];
		if (TableRegistry::get("Users")->save($user)) {
			echo ("{\"status\" : \"success\", \"newrole\" : \"" . $this->request->data["role"]."\"}");
		} else {
			echo ("{\"status\" : \"error\"}");
		}

	}

	function userdiary($id) {
		$diarydays = TableRegistry::get("Diary")->find('all', [ 'conditions' => [ 'users_id' => $id ] ]);
        foreach ($diarydays as $key => $day) {
            $trainigday = TableRegistry::get("Trainingprogramday")->get($day->trainingprogramday_id);
            $day->trainingprogramday = $trainigday;
            $doneex = TableRegistry::get("Doneexercise")->find('all', ["conditions" => ["diary_id" => $day->id]]);
            $day->dayexersices = $doneex->all();
            if ($doneex->count() == 0) {
                $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $day->trainingprogramday_id]]);
                $exes1 = [];
                foreach($planexercises as $key => $planex) {
                    $ex = new \stdClass();
                    $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                    $ex->exercise = $exercise;  
                    $ex->plan = $planex;
                    $sets1 = [];
                    for ($i=0; $i<$planex->podhod; $i++) {
                        $set = new \stdClass();
                        $set->planrepeats = $planex->repeats;
                        $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                        $sets1[count($sets1)] = $set;   
                    }
                    $ex->sets = $sets1;
                    $exes1[count($exes1)] = $ex;
                }
                $day->dayexersices = $exes1;
            } else {
                foreach ($doneex as $key => $ex) {  
                    $exercise = TableRegistry::get("Exercise")->get($ex->exercise_id, ['contain' => ['Musculgroups']]); 
                    $ex->exercise = $exercise;  
                    $plan = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["id" => $ex->trainingdayexercise_id]]);
                            //debug($plan);
                            //var_dump($plan->first());
                    $plan1 = $plan->first();
                    $ex->plan = $plan1;
                    $sets = TableRegistry::get("Doneexerciseset")->find('all', ["conditions" => ["doneexercise_id" => $ex->id]]);
                    $sets1 = $sets->toArray();
                    for ($i=0; $i<count($sets1); $i++) {
                        if ($i<$plan1->podhod) {
                            $sets1[$i]->planrepeats = $plan1->repeats;
                            $sets1[$i]->planweight = $plan1->minweight . " - " . $plan1->maxweight;
                        }
                    }
                    if (count($sets1) < $plan1->podhod)
                        for ($i=count($sets1); $i<$plan1->podhod; $i++) {
                            $set = new \stdClass();
                            $set->planrepeats = $plan1->repeats;
                            $set->planweight = $plan1->minweight . " - " . $plan1->maxweight;
                            $sets1[count($sets1)] = $set;   
                        }
                    $ex->sets = $sets1;
                        //$day->dayexersices->sets = $sets->all();
                }
            }
            //$day->dayexersices = $doneex->all();
        }        

        $this->set("diarydays", $diarydays);
        $this->set("baseurl", "/user");


        $exercises = TableRegistry::get("Doneexercise")->find('all');        

        $this->set("exes", $exercises->all());        

        $this->loadModel("Doneexerciseset");
        $this->loadModel("Doneexercise");
        $requestArticlesDetaile = $this->Doneexerciseset->find('all', [
    		'join'=>['Doneexercise' => [
            'table' => 'doneexercise',
            'type' => 'LEFT',
            'conditions' => 'Doneexerciseset.doneexercise_id = Doneexercise.id'
        		]
    		]

		]);

		$this->set("exesede", $requestArticlesDetaile->all()); 
		$user = TableRegistry::get("Users")->get($id);
		$this->set("userdiary", true);
		$this->set("user", $user);
	}

	private function formatdiaryeatingprogram($program, $bgunorm) {
        $obj = new \stdClass;
            $obj->id = $program->id;
            $obj->name = $program->name;
            $obj->active = $program->active;
            $obj->routine_id = $program->routine_id;
            $key2 = [];
            $key2[$program->routine->routineday[0]->id] = 0;
            $key2[$program->routine->routineday[1]->id] = 1;
            $key3 = [];
            $key4 = [];
            for ($ii=0; $ii < 2; $ii++)
                foreach ($program->routine->routineday[$ii]->eating as $key => $value) 
                    $key3[$value->id] = $program->routine->routineday[$ii]->id;        
                

            $days=[];
            foreach ($program->routineeatingmenu as $key => $menu) {
                if (!array_key_exists($menu->day_number, $key4)) {
                    //for ($ii=0; $ii < 2; $ii++)
                    $ii = $key2[$key3[$menu->eating_id]];
                    $data = [];
                    $key4[$menu->day_number] = count($days);
                    foreach ($program->routine->routineday[$ii]->eating as $key => $value) {
                        //$days[$menu->day_number][(int)$value->id] = (object)['foods' => [], 'eating' => $value, "number" => $key];
                        $data[$key] = (object)['foods' => [], 'eating' => $value, "number" => $key, "norm" => $bgunorm];
                        /*$days[$menu->day_number][$key] = (object)['foods' => [], 'eating' => $value, "number" => $key];*/
                        $keys[$value->id] = $key;
                    }
                    $days[count($days)] = (object)["day_number" => $menu->day_number, "data" => $data];
                }
                //$days[$menu->day_number][(int)$menu->eating_id]->foods[count($days[$menu->day_number][(int)$menu->eating_id]->foods)] = $menu;
                $menu->food->colories = $menu->cnt/100 * $menu->food->colories;
                $menu->food->hidrocarbonats = $menu->cnt/100 * $menu->food->hidrocarbonats;
                $menu->food->fats = $menu->cnt/100 * $menu->food->fats;
                $menu->food->proteins = $menu->cnt/100 * $menu->food->proteins;
                
                //$days[$menu->day_number][$keys[$menu->eating_id]]->foods[count($days[$menu->day_number][$keys[$menu->eating_id]]->foods)] = $menu;
                $days[$key4[$menu->day_number]]->data[$keys[$menu->eating_id]]->foods[count($days[$key4[$menu->day_number]]->data[$keys[$menu->eating_id]]->foods)] = ["plan" => $menu];
            }
            $obj->days = $days;
            foreach($obj->days[0]->data as $key => $value) {
                $total = (object)["fats" => 0, "hidrocarbonats" => 0, "proteins" => 0, "colories" => 0];
                foreach($value->foods as $key2 => $value2) {
                    $total->fats += $value2["plan"]->food->fats;
                    $total->hidrocarbonats += $value2["plan"]->food->hidrocarbonats;
                    $total->proteins += $value2["plan"]->food->proteins;
                    $total->colories += $value2["plan"]->food->colories;
                }
                $value->total = $total;
            }
            //$eatings[$program->id] = $obj;
            return $obj;
    }

    private function formatresults($data, $norm) {
        $obj = new \stdClass;
            $obj->id = $data[0]->eatingprogram->id;
            $obj->name = $data[0]->eatingprogram->name;
            $obj->active = $data[0]->eatingprogram->active;
            $obj->routine_id = $data[0]->eatingprogram->routine_id;
            /*$key2 = [];
            $key2[$data[0]->eatingprogram->routine->routineday[0]->id] = 0;
            $key2[$data[0]->eatingprogram->routine->routineday[1]->id] = 1;
            $key3 = [];
            $key4 = [];*/
            /*for ($ii=0; $ii < 2; $ii++)
                foreach ($data[0]->eatingprogram->routine->routineday[$ii]->eating as $key => $value) 
                    $key3[$value->id] = $data->eatingprogram[0]->routine->routineday[$ii]->id;        */
            $days=[];
            $day = new \stdClass;
            $day->day_number = $data[0]->day_number;
            $eatingdata=[];
            $eatings = [];
            foreach($data as $key => $value) {
                if (!array_key_exists($value->eating_id, $eatings)) {
                    $eatings[$value->eating_id] = count($eatingdata);   
                    $eatingdata[count($eatingdata)] = (object)[];
                    $eatingdata[$eatings[$value->eating_id]]->eating = $value->eating;
                    $eatingdata[$eatings[$value->eating_id]]->foods = [];
                    $eatingdata[$eatings[$value->eating_id]]->norm = $norm;
                    $eatingdata[$eatings[$value->eating_id]]->number = $eatings[$value->eating_id];
                }
                $foodobjreal = (object)[];
                $foodobjreal->food = $value->food;
                $foodobjreal->cnt = $value->cnt;
                $foodobjreal->food->colories = round($foodobjreal->cnt/100 * $foodobjreal->food->colories, 2);
                $foodobjreal->food->proteins = round($foodobjreal->cnt/100 * $foodobjreal->food->proteins, 2);
                $foodobjreal->food->hidrocarbonats = round($foodobjreal->cnt/100 * $foodobjreal->food->hidrocarbonats, 2);
                $foodobjreal->food->fats = round($foodobjreal->cnt/100 * $foodobjreal->food->fats, 2);

                $foodobjplan = null;

                $eatingprogram = TableRegistry::get("Routineeatingmenu")->find('all', ['contain' => ["Food"], 'conditions' => [ 'eating_id' => $value->eating_id, 'food_id' => $value->food_id, 'day_number' => $value->day_number] ]);
                if ($eatingprogram->count() > 0) {

                    

                    $eatingprogram = $eatingprogram->first();
                    $foodobjplan = (object)[];
                    $foodobjplan->food = $eatingprogram->food;
                    $foodobjplan->cnt = $eatingprogram->cnt;    
                    $foodobjplan->food->colories = round($foodobjplan->cnt/100 * $foodobjplan->food->colories, 2);
                $foodobjplan->food->proteins = round($foodobjplan->cnt/100 * $foodobjplan->food->proteins, 2);
                $foodobjplan->food->hidrocarbonats = round($foodobjplan->cnt/100 * $foodobjplan->food->hidrocarbonats, 2);
                $foodobjplan->food->fats = round($foodobjplan->cnt/100 * $foodobjplan->food->fats, 2);
                }

                
                $arr = ["real" => $foodobjreal];
                if ($foodobjplan != null) {
                    $arr["plan"] = $foodobjplan;
                }


                $eatingdata[$eatings[$value->eating_id]]->foods[count($eatingdata[$eatings[$value->eating_id]]->foods)] = $arr;
            }
            $day->data = $eatingdata;
            $days[count($days)] = $day;
        $obj->days = $days;
        foreach($obj->days[0]->data as $key => $value) {
                $total = (object)["fats" => 0, "hidrocarbonats" => 0, "proteins" => 0, "colories" => 0];
                foreach($value->foods as $key2 => $value2) {
                    $total->fats += $value2["real"]->food->fats;
                    $total->hidrocarbonats += $value2["real"]->food->hidrocarbonats;
                    $total->proteins += $value2["real"]->food->proteins;
                    $total->colories += $value2["real"]->food->colories;
                }
                $value->total = $total;
            }
        return $obj;
        
    }

	public function getEatingdiary($date, $user) {
        $this->autoRender = false;
        $uid = $user;
        $profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $uid, 'active' => true ] ]);
        $profile = $profiles->first();
        $this->loadComponent('Diet');

        $activityCoef = $this->Diet->getActivityCoef($profile);
        $age = $this->Diet->getAge($profile);
        $NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
        $NB += $this->Diet->getAimOffset($profile);
        $this->Diet->setAveKkal($NB);

        $bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
        $bgunorm["Kkal"] = round($NB, 2);
        $bgunorm["aveCaCf"] = round($bgunorm["aveCaCf"], 2);
        $bgunorm["aveFtCf"] = round($bgunorm["aveFtCf"], 2);
        $bgunorm["avePrCf"] = round($bgunorm["avePrCf"], 2);


        $existingEating = TableRegistry::get("Eatingdiary")->find('all', ["contain" => ["Eatingprogram", "Eating", "Food"], "conditions" => ["Eatingdiary.date" => $date]]);
        if ($existingEating->count() > 0) {
            $this->set("data", $existingEating->toArray());
            echo "{\"status\" : \"success\", \"data\" : " . json_encode($this->formatresults($existingEating->toArray(), $bgunorm)) . "}";

        }
        else {
            $routine = TableRegistry::get("Routine")->find('all', ["conditions" => 
                ["active" => true, "userId" => $uid]])->first();
            $eatingprograms = TableRegistry::get("Eatingprogram")->find('all', ['conditions' => [ 'users_id' => $uid, 'routine_id' => $routine->id, 'active' => true] ]);
            if ($eatingprograms->count() > 0) {
                $eatingprogram = $eatingprograms->first();
                $date1 = new \DateTime($eatingprogram->date);
                $date2 = new \DateTime($date);
                $diffbetween = $date2->diff($date1)->format("%a");
                //echo($date1->format("Y-m-d"));
                //echo($date2->format("Y-m-d"));
                //echo($diffbetween);
                $daycnt = TableRegistry::get("Routineeatingmenu")->find('all', ['fields' => array('day_number' => 'MAX(Routineeatingmenu.day_number)'), 'conditions' => [ 'eatingprogram_id' => $eatingprogram->id]])->first();
                $daycnt->day_number+=1;
                $curdate = ($diffbetween + 2) % $daycnt->day_number;
                //echo ($diffbetween + 2) . " / " . $daycnt->day_number . " = " .  $curdate;
                $eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ['conditions' => ['day_number' => $curdate], "Food", "Eating"]], 'conditions' => [ 'users_id' => $uid, 'Eatingprogram.id' => $eatingprogram->id] ]);
                echo "{\"status\" : \"success\", \"data\" : " . json_encode($this->formatdiaryeatingprogram($eatingprogram->first(), $bgunorm)) . "}";
            }
            else echo "{\"message\" : \"error\"}";
        }
    }

}