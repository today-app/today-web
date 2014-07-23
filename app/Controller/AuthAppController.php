<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yoophi
 * Date: 2/7/14
 * Time: 10:40 AM
 * To change this template use File | Settings | File Templates.
 */
App::uses('AppController', 'Controller');

class AuthAppController extends AppController {

    public $name = 'AuthApp';
    public $components = array('Auth');
    public $is_logged_in = false;
    public $current_user_id = 0;

    function beforeFilter() {
        parent::beforeFilter();

        if ($this->current_user_id = $this->Auth->user('id')) {
            $this->is_logged_in = true;
        }
        $this->set('is_logged_in', $this->is_logged_in);
        $this->set('current_current_user_id', $this->current_user_id);
    }

}
