<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use App\Controller\AppController;
use User;

/**
 * Error Handling Controller
 *
 * Controller used by ErrorHandler to render error views.
 */
class ErrorController extends AppController
{

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        if ($this->Auth->user()) {
        	if ($this->Auth->user("role") == "corp" || $this->Auth->user("role") == "trainer")
        		$this->viewBuilder()->layout('corpuser');
        	else if ($this->Auth->user("role") == "admin") 
				$this->viewBuilder()->layout('admin');
				else 	
        			$this->viewBuilder()->layout('redesignmain');
        }
        else 
        	$this->viewBuilder()->layout('errorlayout');	
    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $this->set("event", $event);
        //$this->set("obj", $this);
        //var_dump($event);
        $this->viewBuilder()->templatePath('Error');
    }
}
