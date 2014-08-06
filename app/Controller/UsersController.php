<?php
App::uses('AppAuthController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppAuthController {

    public $uses = array('User', 'TodayApi');
//    public $helpers = array('Html', 'Form');

    public function beforeFilter() {
        parent::beforeFilter();
        // Allow users to register and logout.
        $this->Auth->allow('add', 'logout', 'timeline');
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            }
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        $this->request->onlyAllow('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }

    public function timeline() {
        $username = $this->request->params['pass'][0];
        $user = $this->TodayApi->users_get_by_username($username);
        $user_id = $user->id;

        $is_me = false;

        if ($this->currentUserId == $user->id) {
            $is_me = true;
            $incoming = $outgoing = array();
            $incoming_ids = $this->TodayApi->friendship_incoming($this->currentUserId);
            foreach($incoming_ids as $id) {
                $incoming[] = $this->TodayApi->users_get($id);
            }
            $outgoing_ids = $this->TodayApi->friendship_outgoing($this->currentUserId);
            foreach($outgoing_ids as $id) {
                $outgoing[] = $this->TodayApi->users_get($id);
            }
            $this->set(compact('incoming', 'outgoing'));
        } else {

            $is_friend = in_array($user_id, $this->TodayApi->friend_ids($this->currentUserId));
            $is_request_sent =  in_array($user_id, $this->TodayApi->friendship_outgoing($this->currentUserId));
        }

        $user_friends = array();
        $user_friend_ids = $this->TodayApi->friend_ids($user_id);
        foreach ($user_friend_ids as $fid) {
            $user_friends[] = $this->TodayApi->users_get($fid);
        }

        $posts = $this->TodayApi->timeline_user($user->id);
        $this->set(compact('posts', 'user', 'user_friends', 'user_id', 'is_me', 'is_friend', 'is_request_sent'));
    }

    public function accept($target_id = null) {
        if (!$this->request->is('post') && !$this->request->is('put')) {
            throw new MethodNotAllowedException();
        }
        $user = $this->TodayApi->users_get($this->currentUserId);
        $this->TodayApi->friendship_accept($this->currentUserId, $target_id);
        $this->Session->setFlash('Accepted.');
        $this->redirect('/users/' . $user->username);
    }

    public function cancel($target_id = null) {
        if (!$this->request->is('post') && !$this->request->is('put')) {
            throw new MethodNotAllowedException();
        }
        $user = $this->TodayApi->users_get($this->currentUserId);
        $this->TodayApi->friendship_cancel($this->currentUserId, $target_id);
        $this->Session->setFlash('Canceled.');
        $this->redirect('/users/' . $user->username);
    }

    public function remove($target_id = null) {
        if (!$this->request->is('post') && !$this->request->is('put')) {
            throw new MethodNotAllowedException();
        }
        $user = $this->TodayApi->users_get($this->currentUserId);
        $this->TodayApi->friend_remove($this->currentUserId, $target_id);
        $this->Session->setFlash('Removed.');
        $this->redirect('/users/' . $user->username);
    }

    public function reject($target_id = null) {
        if (!$this->request->is('post') && !$this->request->is('put')) {
            throw new MethodNotAllowedException();
        }
        $user = $this->TodayApi->users_get($this->currentUserId);
        $this->TodayApi->friendship_reject($this->currentUserId, $target_id);
        $this->Session->setFlash('Rejected.');
        $this->redirect('/users/' . $user->username);
    }

    public function request($target_id = null) {
        if (!$this->request->is('post') && !$this->request->is('put')) {
            throw new MethodNotAllowedException();
        }
        $user = $this->TodayApi->users_get($this->currentUserId);
        $this->TodayApi->friendship_create($this->currentUserId, $target_id);
        $this->Session->setFlash('Friendship request sent.');
        $this->redirect('/users/' . $user->username);
    }

    public function isAuthorized($user) {
        return true;
    }
}
