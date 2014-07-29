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

    public function view($post_id = null) {
        if (empty($post_id)) {
            $this->setFlash('Error: invalid post_id', '/');
            $this->redirect('/');
        }

        $post = $this->TodayApi->post_get(1, $post_id);
        $comments = $this->TodayApi->post_comment_list(1, $post_id);
        $this->set('post', $post);
        $this->set('comments', $comments);
    }

    public function comment_add() {
        if (!empty($this->request->data)) {
//            pr($this->request->data); exit;
            $post_id = $this->request->data['Comment']['post_id'];
            $text = $this->request->data['Comment']['text'];

            $result = $this->TodayApi->post_comment_create(1, $post_id, $text);
            if ($result) {
                $this->Session->setFlash('Comment added.');
                $this->redirect(array('action' => 'view', $post_id));
            } else {
                $this->Session->setFlash('Some error has occurred!');
            }
        }
    }

}

