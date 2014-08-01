<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppAuthController extends AppController {

    public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'posts',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'pages',
                'action' => 'display',
                'home'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            )
        )
    );

    public function beforeFilter() {
        $this->Auth->allow('index', 'view');
    }

//    public $components = array(
//			'Session',
//			'Auth' => array(
//				'authenticate' => array(
//					'Npub' => array(
//						'userModel' => 'User',
//						'scope' => array('OR' => array(array('User.Role' => 'ROLE_USER'), array('User.ROLE' => 'ROLE_ADMIN'))),
//						'fields' => array('username' => 'username',
//							              'password' => 'password'),
//						)
//					)
//				));
//	public $helpers = array('Session', 'Html', 'Form');
//    public $currentUserId = null;
//
//    function beforeFilter() {
////    	if ($this->isJsonRequest() && $this->acceptJsonRequest()) {
////            if (!$this->Auth->User('id')) {
////                $this->viewClass = 'Json';
////                $this->set('message', 'Not logged in');
////                $this->set('_serialize', array('message'));
////                $this->render();
////            }
////    	}
//        parent::beforeFilter();
//        $this->currentUserId = $this->Auth->User('id');
//    }

}
