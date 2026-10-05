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

class GuideController extends AppController
{
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if ($this->Auth->user("role") == "corp")
        	$this->viewBuilder()->layout('corpuser');
        else	
        	$this->viewBuilder()->layout('redesignmain');
        $this->set("section", "guide");
    }

	function Exercises() {
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];

		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		
		$this->set("musculgroups", $musculgroups);
	}

	function Products() {
		/*$products = TableRegistry::get("Food")->find('all');
		
		$this->set("products", $products);*/
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];
		$foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("foodcategories", $foodcategories);
	}

	function createmusculgroup() {
		if ($this->request->is("post")) {

			$musculgroups = TableRegistry::get("Musculgroup");
			$musculgroup = $musculgroups->newEntity();
			$musculgroup = $musculgroups->patchEntity($musculgroup, $this->request->data);
			$musculgroup->owner = $this->Auth->user('id');
			
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
					return $this->redirect(['action' => 'exercises']);	
				}
			}
		}
	}

	function editmusculgroup() {
		if ($this->request->is("post")) {

			$musculgroups = TableRegistry::get("Musculgroup");
			$musculgroup = $musculgroups->get($_POST['id']);
			$musculgroup->name = $_POST['name'];
			$musculgroup->owner = $this->Auth->user('id');
			
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
					return $this->redirect(['action' => 'exercises']);	
				}
			}
		}
	}

	function deletemusculgroup($id)
	{
		$this->autoRender = false;

		$musculgroups = TableRegistry::get("Musculgroup");
		$musculgroup = $musculgroups->get($id);
		//$musculgroups->delete($musculgroup);
		$musculgroup->deleted = 1;
		$musculgroups->save($musculgroup);
		return $this->redirect(['action' => 'exercises']);
	}

	function createexercise() {
			$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];

		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
			$this->set("musculgroups", $musculgroups);
			if ($this->request->is("post")) {
	
				$exercises = TableRegistry::get("Exercise");
				$exercise = $exercises->newEntity();
				//$exercise = $exercises->patchEntity($exercise, $this->request->data);
	
				$exercise->name = $this->request->data['name'];
				$exercise->owner = $this->Auth->user('id');
				$exercise->description = $this->request->data['description'];
				$target_path = WWW_ROOT . 'img/excersices/';
				$exercise->img = $this->request->data['img']['name'];
				$exercise->video = ($this->request->data['video']['name'] != '')?$this->request->data['video']['name']:null;
	
				   /*if ($musculgroups->save($musculgroup)) {
	
								$this->Flash->Success('Упражнение успешно добавлена');
								return $this->redirect(['action' => 'index']);	
							}*/
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
						
						//Грузим видео
						$target_path = WWW_ROOT . 'video/exercises/';
						$file_name = $this->request->data['video']['name'];
				//$this->set("files", $this->request->data);/*
						$parts = explode(".", $file_name);
						$ext = pathinfo($file_name, PATHINFO_EXTENSION);
						$fname = $parts[0];
						$new_file_name = $fname .  "." . $ext;
				//$path = $new_file_name;
						$to_path = $target_path . $fname . "." . $ext; //set the target path with a new name of image
				//echo $path;
	
						if ($file_name != '') {
	
							if (!move_uploaded_file($_FILES['video']['tmp_name'], $to_path)) {
				
	
									$this->Flash->error("Не удалось загрузить файл.");
	
								
							} 
						}
					}
				} else {
						$this->Flash->error("Не удалось загрузить файл.");
					}
						$validator = $exercises->validationDefault(new Validator());
								$errors = $validator->errors($this->request->data());
								if (!empty($errors)) {
									$this->set("exercise", $exercise);
									$this->set("error", $errors);
									$this->Flash->error($errors);
								}
									//echo($errors);
								else {
	
									if ($exercises->save($exercise)) {
	
										$exercisesmusculgroups = TableRegistry::get("ExerciseMusculgroup");
										$exercisesmusculgroup = $exercisesmusculgroups->newEntity();
										$exercisesmusculgroup->musculgroup_id = $this->request->data['musculgroup'];
										$exercisesmusculgroup->exercise_id = $exercise->id;
										$exercisesmusculgroups->save($exercisesmusculgroup);
										$this->Flash->Success('Упражнение успешно добавлено');
										return $this->redirect(['action' => 'exercises']);	
									}
								}
					//} 
				//}
	
				
			}
	}

	public function editexercise($id = null) {
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
					return $this->redirect(['action' => 'exercises']);	
				}
			}
		}
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];

		$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("musculgroups", $musculgroups);
		$this->set("edit", true);
		$this->set("exercise", $exercise);
		$this->render('createexercise');
	}

	public function deleteexercise($id) {

		$this->autoRender = false;

		$exersices = TableRegistry::get("Exercise");
		$exersice = $exersices->get($id);
		//$exersices->delete($exersice);
		$exersice->deleted = 1;
		$exersices->save($exersice);
		return $this->redirect(['action' => 'exercises']);
	}

	function createproduct() {
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];
		$foodcategories = TableRegistry::get("Foodcategory")->find('all', ["conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("foodcategories", $foodcategories);
		if ($this->request->is("post")) {
			$foods = TableRegistry::get("Food");
			$food = $foods->newEntity();
			$food->owner = $this->Auth->user('id');
			$food->name = $this->request->data['name'];
			$food->category_id = $this->request->data['foodcategory'];
			$food->proteins = $this->request->data['proteins'];
			$food->fats = $this->request->data['fats'];
			$food->hidrocarbonats = $this->request->data['hidrocarbonats'];
			$food->colories = $this->request->data['colories'];
			$foods->save($food);
			$this->Flash->Success('Продукт успешно добавлен');
			return $this->redirect(['action' => 'products']);
		}
	}

	public function editproduct($id) {
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		$productId = $id;
		$id = $this->Auth->user('id');
		$ids = [$id, $admin->first()->id];
		$foodcategories = TableRegistry::get("Foodcategory")->find('all', ["conditions" => ["deleted" => 0, "owner IN" => $ids]]);
		$this->set("foodcategories", $foodcategories);
		$food = TableRegistry::get("Food")->get($productId, []);
		if ($this->request->is("post")) {
			$foods = TableRegistry::get("Food");
			$food->name = $this->request->data['name'];
			$food->category_id = $this->request->data['foodcategory'];
			$food->proteins = $this->request->data['proteins'];
			$food->fats = $this->request->data['fats'];
			$food->hidrocarbonats = $this->request->data['hidrocarbonats'];
			$food->colories = $this->request->data['colories'];
			$foods->save($food);
			$this->Flash->Success('Продукт успешно добавлен');
			return $this->redirect(['action' => 'products']);
		}

		$this->set("food", $food);
		$this->set("edit", true);
		$this->render('createproduct');
	}

	public function deleteproduct($id) {

		$this->autoRender = false;

		$foods = TableRegistry::get("Food");
		$food = $foods->get($id);
		$food->deleted = 1;
		$foods->save($food);
		return $this->redirect(['action' => 'products']);
	}

	function createproductgroup() {
		if ($this->request->is("post")) {

			$foodcategories = TableRegistry::get("Foodcategory");
			$foodcategorie = $foodcategories->newEntity();
			$foodcategorie = $foodcategories->patchEntity($foodcategorie, $this->request->data);
			$foodcategorie->owner = $this->Auth->user('id');
			
			$validator = $foodcategories->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				$this->set("foodcategorie", $foodcategorie);
				$this->Flash->error($errors);
			}
				//echo($errors);
			else {

				if ($foodcategories->save($foodcategorie)) {

					$this->Flash->Success('Категория продуктов успешно добавлена');
					return $this->redirect(['action' => 'products']);	
				}
			}
		}
	}

	public function editfoodcategory() {

		$foodcategories = TableRegistry::get("Foodcategory");

		if ($this->request->is("post")) {
			$foodcategorie = $foodcategories->get($_POST['id']);
			$foodcategorie->name = $_POST['name'];
			$validator = $foodcategories->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				//$this->set("musculgroup", $musculgroup);
				$this->Flash->error($errors);
			}
				//echo($errors);
			else {

				if ($foodcategories->save($foodcategorie)) {

					$this->Flash->Success('Категория продуктов успешно изменена');
					return $this->redirect(['action' => 'products']);	
				}
			}
		}
	}

	public function deletefoodcategory($id) {
		$this->autoRender = false;

		$foodcategories = TableRegistry::get("Foodcategory");
		$foodcategorie = $foodcategories->get($id);
		$foodcategorie->deleted = 1;
		$foodcategories->save($foodcategorie);

		return $this->redirect(['action' => 'products']);
	}
}