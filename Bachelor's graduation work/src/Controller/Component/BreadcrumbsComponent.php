<?php
//app/Controller/Component/BreadcrumbsComponent.php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
 
/**
* Компонент для управления отображением "хлебными крошками"
*/
class BreadcrumbsComponent extends Component {
	private $breadcrumbs = array();
 
	public function initialize(Controller $controller) {
		$this->add(__('Главная'), Router::url('/'));
	}
 
	public function beforeRender(Controller $controller) {
		$controller->set('breadcrumbs', $this->breadcrumbs);
	}
 
	/**
	* Добавление ссылки
	*
	* @param string|array $title Если передано string то добавляется одна
	*      ссылка указывающая на $url.
	*        Если передавать array, то будет добавлена группа ссылок, но
	*        в каждом элементе массива обязательно должны быть ключи 'title' и
	*        'url'.
	* @param string $url Строка представляющая относительную ссылку. Значение
	*        параметра в случае необходимости должно быть заранее подготовлено
	*        с помощью функции Router::url().
	* @return boolean В случае ошибки возвращается false иначе true
	*/
	public function add($title, $url = '', $icon = '') {
		if (is_array($title)) {
			return $this->_add($title);
		}
		if (empty($this->breadcrumbs) || ($this->breadcrumbs[count($this->breadcrumbs) -1]['url'] != $url)) {
			$this->breadcrumbs[] = array(
				'title' => $title,
				'url' => $url,
				'icon' => $icon
			);
		}
		return true;
	}
 
	public function clear() {
		$this->breadcrumbs = array();
		return true;
	}
 
	/**
	* Добавление списка ссылок
	*
	* @param array $params Массив с описанием ссылок. В каждом элементе массива
	*        обязательно должны присутствовать ключи 'title' и 'url'.
	*/
	private function _add($params) {
		if (!is_array($params)) return false;
		foreach ($params as $param) {
			$this->add($param['title'], $param['url']);
		}
		return true;
	}
}