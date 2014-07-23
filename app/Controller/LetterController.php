<?php
App::uses('AppController', 'Controller');

/**
 * @property mixed LetterApi
 */
class LetterController extends AppController {

    public $name = 'Letter';
    public $uses = array('LetterApi');

    function index() {
        $filter = array();
        foreach(array('cheer_id', 'category_id', 'user_id') as $key) {
            if (!empty($this->request->query[$key])) {
                $var = "current_$key";
                $$var = $this->request->query[$key];
                $filter = $filter + array($key => (int) $$var);
                $this->set($var, $$var);
            }
        }

        $params = array();
        foreach(array('limit', 'max_id', 'since_id') as $key) {
            if (!empty($this->request->query[$key])) {
                $params[$key] = (int) $this->request->query[$key];
                $this->set($key, $params[$key]);
            }
        }


        list($code, $result) = $this->LetterApi->all($filter, $params);

        if ($code == API_SERVICE_ERROR) {
            $this->Session->setFlash($result['message']);
            $this->redirect('/');
        }
        $this->set('letters', $result);
    }

    function get($id) {
        list($code, $result) = $this->LetterApi->get(1, $id);
        list($code2, $result2) = $this->LetterApi->comment_list(1, $id);

        if ($code == API_SERVICE_ERROR) {
            $this->Session->setFlash($result['message']);
            $this->redirect('/');
        }
        $this->set('letter', $result);
        $this->set('comments', $result2);
        $this->set('letter_id', $result['id']);
    }

    function comment_add() {
        if (!empty($this->request->data)) {
            $data = $this->request->data['Letter'];
            $letter_id = $data['letter_id'];

            unset($data['letter_id']);
            list($code, $response) = $this->LetterApi->comment_create($this->current_user_id, $letter_id, $data);
            $this->Session->setFlash('댓글을 작성했습니다.');
        }
        $this->redirect(array('action' => 'get', $letter_id));
    }

    function comment_delete($letter_id, $comment_id) {
        $redirect = array('action' => 'get', $letter_id);

        if (!empty($comment_id) && !empty($letter_id)) {
            list($code, $result) = $this->LetterApi->comment_remove($this->current_user_id, $letter_id, $comment_id);

            if ($code == API_SERVICE_ERROR) {
                $message = $result['message'];
            } elseif ($code == 404) {
                $message = '존재하지 않는 comment_id 입니다.';
            } else {
                $message = '삭제하였습니다.';
            }
        } else {
            $message = '잘못된 접근입니다.';
        }

        $this->Session->setFlash($message);
        $this->redirect($redirect);
    }

}
