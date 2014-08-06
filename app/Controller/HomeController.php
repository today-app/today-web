<?php
App::uses('AppAuthController', 'Controller');

class HomeController extends AppAuthController {

    public $uses = array('TodayApi');

    public function index() {
        $posts = $this->TodayApi->timeline_home($this->currentUserId);
        $this->set('posts', $posts);
    }

    public function posts() {
    }

    public function notifications() {
    }

}

