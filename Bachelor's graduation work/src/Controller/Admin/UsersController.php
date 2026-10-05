<? 
namespace App\Controller\Admin;


use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validator;

class UsersController extends AppController
{


	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('admin');
        $this->set("section", "user");
    }

    public function isAuthorized($user) {
        
        return true;
    }

    public function index() {

    	$users = TableRegistry::get("Users")->find('all', []);
		
		$this->set("users", $users);

    }

    private function getFullYears($birthdayDate) {
        //Log::write('debug', $birthdayDate);
        $datetime = new \DateTime($birthdayDate);
        //Log::write('debug', $datetime);
        $interval = $datetime->diff(new \DateTime(date("Y-m-d")));
        return $interval->format("%Y");
	}

	public function userinfo($uid) {
    	$users = TableRegistry::get("Users")->find('all', []);
		
		$this->set("users", $users);
		$this->set("activeuser", $uid);
		$this->render('index');
    }

    public function getuserinfo() {
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
			$p->trainid = $user->trainer;
			
			$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id ]] )->count();
			$currentroutine = null;
			if ($routines > 0)
			$currentroutine = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id, 'active' => 1 ] ])->first();
			$trainingprograms = TableRegistry::get("Trainingprogram")->find('all', [ 'conditions' => [ 'users_id' => $id ] ])->count();
			$currentprogram = null;
			if (TableRegistry::get("Trainingprogram")
				->find('all', ['contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $id, "active" => 1 ] ])->count() > 0)
			$currentprogram = TableRegistry::get("Trainingprogram")
				->find('all', ['contain' => ['trainingprogramday'=>[
	            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]], 'conditions' => [ 'users_id' => $id, "active" => 1 ] ])->first();
			$eatings = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine'], 'conditions' => [ 'userId' => $id ] ])->count();
			$eatingprogram = null;
			if ($currentroutine!=null && TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'userId' => $id, 'routine_id' => $currentroutine->id, "Eatingprogram.active" => 1] ])->count() > 0)
			$eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'userId' => $id, 'routine_id' => $currentroutine->id, "Eatingprogram.active" => 1] ])->first();

			
			$stat = (object) array('routine' => $routines, 'trainingprogram' => $trainingprograms, 'eatings' => $eatings);

			$data = "{\"status\": \"success\", \"profile\" : " . json_encode($p) . ", \"stat\" : " . json_encode($stat) . ", \"currentroutine\" : " . json_encode($currentroutine) . ", \"program\" : " . json_encode($currentprogram) . ", \"eating\" : " . json_encode($eatingprogram) . "}";

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

}