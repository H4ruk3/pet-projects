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

class FoodController extends AppController
{


	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('admin');
        $this->set("section", 'help');
    }

    public function isAuthorized($user) {
        
        return true;
    }

    public function index() {

    	//$users = TableRegistry::get("Users")->find('all', []);
    	$uid = $this->Auth->user('id');
        $ids = [$uid];
        $foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("foodcategories", $foodcategories);
		
		//$this->set("users", $users);

    }

    public function create() {
    	$uid = $this->Auth->user('id');
		$foodcategories = TableRegistry::get("Foodcategory")->find('all', ['conditions' => ['deleted' => 0, 'owner' => $uid]]);
		$this->set("foodcategories", $foodcategories);
		if ($this->request->is("post")) {
			$foods = TableRegistry::get("Food");
			$food->owner = $this->Auth->user('id');
			$food = $foods->newEntity();
			$food->name = $this->request->data['name'];
			$food->foodcategory_id = $this->request->data['foodcategory'];
			$food->proteins = $this->request->data['proteins'];
			$food->fats = $this->request->data['fats'];
			$food->hidrocarbonats = $this->request->data['hidrocarbonats'];
			$food->colories = $this->request->data['colories'];
			$foods->save($food);
			$this->Flash->Success('Продукт успешно добавлен');
			return $this->redirect(['action' => 'index']);
		}
	}

/***********************************
 * Изменение продукта
 *************************************/	
	public function edit($id) {
		$uid = $this->Auth->user('id');
		$foodcategories = TableRegistry::get("Foodcategory")->find('all', ['conditions' => ['deleted' => 0, 'owner' => $uid]]);
		$this->set("foodcategories", $foodcategories);
		$food = TableRegistry::get("Food")->get($id, []);
		if ($this->request->is("post")) {
			$foods = TableRegistry::get("Food");
			$food->name = $this->request->data['name'];
			$food->foodcategory_id = $this->request->data['foodcategory'];
			$food->proteins = $this->request->data['proteins'];
			$food->fats = $this->request->data['fats'];
			$food->hidrocarbonats = $this->request->data['hidrocarbonats'];
			$food->colories = $this->request->data['colories'];
			$foods->save($food);
			$this->Flash->Success('Продукт успешно добавлен');
			return $this->redirect(['action' => 'index']);
		}

		$this->set("food", $food);
		$this->set("edit", true);
		$this->render('create');
	}
	

	public function edit2($id = null) {
		$exercise = null;
		if ($id != null)
			//$exercise = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => array("conditions" => array("Exercises.id" => $id))]])->all();
			$exercise = TableRegistry::get("Exercise")->find('all',  [
				"contain" => ["Musculgroups"], "conditions" => ["id" => $id]])->first();
			
		if ($this->request->is("post")) {
			$exercises = TableRegistry::get("Exercise");
			$exercise = $exercises->get($this->request->data["id"]);
			$exercise->name = $this->request->data['name'];
			$exercise->description = $this->request->data['description'];
			if ($this->request->data['img']['name'] != null && $this->request->data['img']['name'] != "") {
				//Delete old image
				$target_path = WWW_ROOT . 'img/excersices/';
				if ($exercise->img != null && $exercise->img != "")
					unlink($target_path.$exercise->img);
				$exercise->img = $this->request->data['img']['name'];
				$file_name = $this->request->data['img']['name'];
				//$this->set("files", $this->request->data);/*
				$parts = explode(".", $file_name);
				$fname = $parts[0];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_file_name = $fname .  "." . $ext;
				//$path = $new_file_name;
				$to_path = $target_path . $fname . "." . $ext; //set the target path with a new name of image
				//echo $path;

				if ($file_name != '') {

					if (move_uploaded_file($_FILES['img']['tmp_name'], $to_path)) {
					}
				}
			}
			if ($this->request->data['video']['name'] != null && $this->request->data['img']['name'] != "") {
				//Delete old image
				$target_path = WWW_ROOT . 'video/excersices/';
				if ($exercise->video != null && $exercise->video != "")
					unlink($target_path.$exercise->video);
				$exercise->video = $this->request->data['video']['name'];
				$file_name = $this->request->data['video']['name'];
				//$this->set("files", $this->request->data);/*
				$parts = explode(".", $file_name);
				$fname = $parts[0];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_file_name = $fname .  "." . $ext;
				//$path = $new_file_name;
				$to_path = $target_path . $fname . "." . $ext; //set the target path with a new name of image
				//echo $path;

				if ($file_name != '') {

					if (move_uploaded_file($_FILES['video']['tmp_name'], $to_path)) {
					}
				}
			}
			$validator = $exercises->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				$this->set("exercise", $exercise);
				$this->set("error", $errors);
				$this->Flash->error($errors);
			}
			else {
				if ($exercises->save($exercise)) {
					//echo($exercise->id);
					$exercisesmusculgroups = TableRegistry::get("ExerciseMusculgroup");
					$exercisesmusculgroups->deleteAll([ 'exercise_id' => $exercise->id ]);
					$exercisesmusculgroup = $exercisesmusculgroups->newEntity();
					$exercisesmusculgroup->musculgroup_id = intval($this->request->data['musculgroup']);
					$exercisesmusculgroup->exercise_id = $exercise->id;
					$exercisesmusculgroups->save($exercisesmusculgroup);
					$this->Flash->Success('Упражнение успешно изменено');
					return $this->redirect(['action' => 'index']);	
				}
			}
		}
		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises"]]);
		$this->set("musculgroups", $musculgroups);
		$this->set("edit", true);
		$this->set("exercise", $exercise);
		$this->render('create');
	}

    public function createmusculgroup() {
    	if ($this->request->is("post")) {

			$musculgroups = TableRegistry::get("Musculgroup");
			$musculgroup = $musculgroups->newEntity();
			$musculgroup = $musculgroups->patchEntity($musculgroup, $this->request->data);
			
			$validator = $musculgroups->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				$this->set("musculgroup", $musculgroup);
				$this->Flash->error($errors);
			}
				//echo($errors);
			else {

				if ($musculgroups->save($musculgroup)) {

					$this->Flash->Success('Группа мышц успешно добавлена');
					return $this->redirect(['action' => 'index']);	
				}
			}
		}
	}
	
	public function deletefile() {
		$this->autoRender = false;
		if ($this->request->is("post")) {
			$exercises = TableRegistry::get("Exercise");
			$exercise = $exercises->get($this->request->data["id"]);
			if ($this->request->data["type"] = "ïmg" && $exercise->img != null) {
				$target_path = WWW_ROOT . 'img/excersices/';
				unlink($target_path.$exercise->img);
				$exercise->img = null;
				$exercises->save($exercise);
				echo "{\"status\": \"success\", \"message\" : \"Файл успешно удалён\"}";
			} else if ($this->request->data["type"] = "video" && $exercise->video != null) {
				$target_path = WWW_ROOT . 'video/excersices/';
				unlink($target_path.$exercise->video);
				$exercise->video = null;
				$exercises->save($exercise);
				echo "{\"status\": \"success\", \"message\" : \"Файл успешно удалён\"}";
			} else {
				echo "{\"status\": \"error\", \"message\" : \"Неверный тип ресурса\"}";	
			}
		} else {
			echo "{\"status\": \"error\", \"message\" : \"Неверный запрос\"}";
		}
	}

    public function delete($id) {

		$this->autoRender = false;

		$foods = TableRegistry::get("Food");
		$food = $foods->get($id);
		$food->deleted = 1;
		$foods->save($food);
		return $this->redirect(['action' => 'index']);
	}

}