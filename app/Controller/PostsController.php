<?php
App::uses('AppController', 'Controller');

class PostsController extends AppController {

    public $uses = array('TodayApi');

    public function index() {
        $this->set('posts', $this->TodayApi->post_list(1));
    }

    public function add() {
        if (!empty($this->request->data)) {
            $text = $this->request->data['Post']['text'];
            $result = $this->TodayApi->post_create(1, $text);
            if ($result) {
                $this->Session->setFlash('Created');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Some error has occurred!');
            }
        }
    }

}

