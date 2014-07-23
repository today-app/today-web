<?php
App::uses('AuthAppController', 'Controller');

/**
 * @property mixed LetterApi
 */
class MyLetterController extends AuthAppController {

    public $name = 'MyLetter';
    public $uses = array('LetterApi');

    function index() {
        $this->redirect(array('controller' => 'letter', 'action' => 'index', '?' => array('user_id' => $this->Auth->user('id'))));
    }

    function create() {
        if (!empty($this->request->data)) {
            $data = $this->request->data;

            $letter = array(
                'title' => $data['Letter']['title'],
                'text' => $data['Letter']['text'],
                'category_id' => (int) $data['Letter']['category_id'],
            );
            if (!empty($data['Letter']['cheer_id'])) {
                $letter['cheer_id'] = (int) $data['Letter']['cheer_id'];
            }

            $extra = $link = $bgmusic = array();
            if (!empty($data['Letter']['link_title']) && !empty($data['Letter']['link_url'])) {
                $link = array(
                    'title' => $data['Letter']['link_title'],
                    'url' => $data['Letter']['link_url'],
                );
            }
            if (!empty($data['Letter']['bgmusic_url'])) {
                $bgmusic = array(
                    'url' => $data['Letter']['bgmusic_url'],
                );
            }

            $letter_type = 'plain';
            if (!empty($data['Letter']['letter_type'])) {
                $letter_type = $data['Letter']['letter_type'];
            }

            if ($letter_type == 'book') {
                $book = array();
                $book['id'] = (int) $data['Letter']['book_id'];
                $book['title'] = $data['Letter']['book_title'];
                $book['author'] = $data['Letter']['book_author'];
                $book['cover_url'] = $data['Letter']['book_cover_url'];
                $extra['book'] = $book;
                $extra['quote'] = $data['Letter']['book_quote'];
                $extra['text'] = $data['Letter']['book_text'];
            } elseif ($letter_type == 'photo') {
                $photo['url'] = $data['Letter']['photo_url'];
                $photo['path'] = $data['Letter']['photo_path'];
                $photo['width'] = (int) $data['Letter']['photo_width'];
                $photo['height'] = (int) $data['Letter']['photo_height'];
                $extra = array($photo);
            } elseif ($letter_type == 'video') {
                $extra['type'] = $data['Letter']['video_type'];
                $extra['url'] = $data['Letter']['video_url'];
            } elseif ($letter_type == 'voice') {
                $extra['url'] = $data['Letter']['voice_url'];
            }

            $result = $this->LetterApi->create(
                $this->current_user_id,
                $letter_type,
                $letter,
                $extra,
                $link,
                $bgmusic
            );

            if ($result->code == 200) {
                $data = json_decode($result->result);
                $this->redirect(array('controller' => 'letter', 'action' => 'get', $data->id));
            } else {
                $this->Session->setFlash('Error occurred.');
            }
        }
    }

    function delete($letter_id) {
        $redirect = array('action' => 'index');

        if (!empty($letter_id)) {
            list($code, $result) = $this->LetterApi->remove($this->current_user_id, $letter_id);

            if ($code == API_SERVICE_ERROR) {
                $message = $result['message'];
            } elseif ($code == 404) {
                $message = '존재하지 않는 letter_id 입니다.';
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
