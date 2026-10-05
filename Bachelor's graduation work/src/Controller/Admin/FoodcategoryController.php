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

class FoodcategoryController extends AppController
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
		$foodcategories = TableRegistry::get("Foodcategory")->find('all', ['conditions' => ['deleted' => 0, 'owner' => $uid]]);
		$this->set("foodcategories", $foodcategories);
		
		//$this->set("users", $users);

    }

	public function create() {
    	if ($this->request->is("post")) {

			$foodcategories = TableRegistry::get("Foodcategory");
			$foodcategorie = $foodcategories->newEntity();
			$foodcategorie = $foodcategories->patchEntity($foodcategorie, $this->request->data);
			
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
					return $this->redirect(['action' => 'index']);	
				}
			}
		}
    }

    public function edit() {

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
					return $this->redirect(['action' => 'index']);	
				}
			}
		}
		//return $this->redirect(['action' => 'index']);
	}

    public function delete($id) {

		$this->autoRender = false;

		$foodcategories = TableRegistry::get("Foodcategory");
		$foodcategorie = $foodcategories->get($id);
		$foodcategorie->deleted = 1;
		$foodcategories->save($foodcategorie);

		return $this->redirect(['action' => 'index']);
	}

}