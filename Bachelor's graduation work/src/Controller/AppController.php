<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Websocket\Lib\Websocket;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public $pathPrev = "";
    public $pathCur = "";

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [

            'authorize' => 'Controller',
            'loginAction' => ['controller' => 'Auth', 'action' => 'index'],
            'loginRedirect' => [
                'controller' => 'profile',
                'action' => 'index'
            ],
        ]);
        //$this->loadComponent('Breadcrumbs');

        $this->loadModel('Users');
        $this->loadModel('Profiles');

        //Проверяем если профиль не создан, то редиректим на создание профиля.
        //var_dump($this->params);

        /*if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            //$this->layout = 'admin';
            $this->viewBuilder()->layout('admin');
        } else {
*/
        $this->Auth->allow(['login', 'password', 'reset']);
        if ($this->Auth->user())
        {            
            $profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $this->Auth->user()["id"], 'active' => true] ]);
            $user = $this->Auth->user();
            if ($profiles->count()>0)
                $user["profile"] = $profiles->first();
            $this->set("user", $user);
            $controller = $this->request->params['controller'];
            $action = $this->request->params['action'];

            
            /*if ($this->Auth->user()['role'] == 'admin')
                        $this->redirect(['prefix' => 'admin', 'controller' => 'users', 'action' => 'index']);*/
            //var_dump("expression");
            /*if ($this->Auth->user()['role'] == 'admin')
                    $this->redirect(['controller' => 'profile', 'action' => 'view']);*/
            /*if ($this->request->params['prefix'] == 'admin') {
                $this->viewBuilder()->layout('admin');
            } else {
            if (!($controller == "auth" || ($controller = "profile" && in_array($action, ['create', 'edit']))))
            {
                $id = $this->Auth->user('id');
                $profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id ] ]);
                if ($profiles->count() == 0) {
                    if ($this->Auth->user()['role'] == 'admin')
                        $this->redirect(['prefix' => 'admin', 'controller' => 'users', 'action' => 'index']);
                    $this->redirect(['controller' => 'profile', 'action' => 'create']);
                }
            $this->set('pathPrev', $this->referer()); 
            } else {
            
            if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
            //$this->layout = 'admin';
                if ($this->Auth->user()['role'] == 'admin')
                    $this->viewBuilder()->layout('admin');
                /*else
                    $this->redirect(['controller' => 'main', 'action' => 'index']);*/       
          /*  } else {
                $this->set('pathPrev', $this->referer()); 
                //$this->redirect($this->referer());
            }
        }}*/
        } else {

  //      }
    }
        /*if ($this->Auth->user())
            $this->set('pathPrev', $this->referer());  */
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {

        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        /*if ($user = $this->Session->read('Auth.User')) {

        }*/
    }

    public function beforeFilter(Event $event)
    {
        //$this->set('params', $this->request->params);
        //var_dump($this); 
        /*$this->FrontendBridge->setJson('websocketFrontendConfig', Websocket::getFrontendConfig());  */
    }

    public function isAuthorized($user) {
        
        return true;
    }

    public function getUser($apiKey) {

        $user = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]])->first();
        return $user;
    }

    public function ok() {
        return '{"status: OK"}';
    }

    public function err() {
        return '{"status: ERROR"}';
    } 
}
