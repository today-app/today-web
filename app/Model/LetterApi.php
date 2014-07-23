<?php
require_once APP . 'Vendor/lib/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Exception\TException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TSocket;

$GEN_DIR = APP . 'Vendor/gen-php';
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', APP . "Vendor/lib");
$loader->registerDefinition('shared', $GEN_DIR);
$loader->registerDefinition('letter', $GEN_DIR);
$loader->register();

/**
 * @property TFramedTransport transport
 * @property TBinaryProtocol protocol
 * @property LetterApiServiceClient client
 */
class LetterApi extends AppModel {

    public $client = null;
    public $useTable = false;

    private function __initThrift() {
        $backend = Configure::read('Backend');

        if (!is_null($this->client)) {
            return true;
        }

        try {
            $socket = new TSocket($backend['hostname'], $backend['port']);
            $socket->setRecvTimeout(10000);
            $this->transport = new TFramedTransport($socket, 1024, 1024);
            $this->protocol = new TBinaryProtocol($this->transport);
            $this->client = new \letter\LetterApiServiceClient($this->protocol);

        } catch (TException $tx) {
            print 'TException: ' . $tx->getMessage() . "\n";
            pr($tx);
        } catch (Exception $e) {
            pr($e);
        }

    }

    public function get($user_id, $letter_id) {
        try {
            $this->__initThrift();
            $this->transport->open();
            $response = $this->client->letters_get($user_id, $letter_id);
            $this->transport->close();
            $code = $response->code;
            if ($code == 200) {
                $result = json_decode($response->result, true);
            }
        } catch (Exception $e) {
            $code = API_SERVICE_ERROR;
            $result['message'] = $e->getMessage();
        }

        return array($code, $result);
    }

    public function remove($user_id, $letter_id) {
        $result = array();
        try {
            $this->__initThrift();
            $this->transport->open();
            $response = $this->client->letters_remove($user_id, $letter_id);
            $this->transport->close();
            $code = $response->code;
            if ($code == 200) {
                $result = json_decode($response->result, true);
            }
        } catch (Exception $e) {
            $code = API_SERVICE_ERROR;
            $result['message'] = $e->getMessage();
        }

        return array($code, $result);
    }

    public function __letters_list($filter = null, $params = null) {
        $result = array();

        $limit = 20;
        $max_id = null;
        $since_id = null;

        if (empty($filter)) {
            $filter_json = '{}';
        } else {
            $filter_json = json_encode($filter);
        }

        if (!empty($params)) {
            foreach (array('limit', 'max_id', 'since_id') as $key) {
                if (isset($params[$key])) {
                    $$key = (int)$params[$key];
                }
            }
        }
        $params = array();
        $params['limit'] = $limit;
        foreach (array('max_id', 'since_id') as $key) {
            if (!empty($$key)) {
                $params[$key] = $$key;
            }
        }
        $params_json = json_encode($params);

        try {
            return $this->client->letters_list($filter_json, $params_json);
        } catch (letter\JsonError $e) {
            throw new Exception($e->why);
        } catch (letter\InputValidationError $e) {
            throw new Exception($e->why);
        }
    }


    public function __letters_create($user_id, $letter_type, $target, $letter, $extra, $link, $bgmusic) {
        $json_letter = json_encode($letter);
        $json_target = json_encode($target);
        $json_extra = json_encode($extra);
        $json_link = json_encode($link);
        $json_bgmusic = json_encode($bgmusic);;

        try {
            $response = $this->client->letters_create(
                $user_id,
                $letter_type,
                $json_target,
                $json_letter,
                $json_extra,
                $json_link,
                $json_bgmusic
            );

        } catch (letter\JsonError $e) {
            throw new Exception($e->getMessage());
        } catch (letter\InputValidationError $e) {
            throw new ValidationError($e->why);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

//        return $response;
        return json_decode($response->result, true);
    }

    public function __letters_update($user_id, $letter_id, $letter_type, $target, $letter, $extra, $link, $bgmusic) {
        $json_letter = json_encode($letter);
        $json_target = json_encode($target);
        $json_extra = json_encode($extra);
        $json_link = json_encode($link);
        $json_bgmusic = json_encode($bgmusic);;

        try {
            $response = $this->client->letters_update(
                $user_id,
                $letter_id,
                $letter_type,
                $json_target,
                $json_letter,
                $json_extra,
                $json_link,
                $json_bgmusic
            );

        } catch (letter\JsonError $e) {
            throw $e;
        } catch (letter\InputValidationError $e) {
            throw $e;
        } catch (letter\InvalidRequest $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $response;
    }

    public function __comment_list($user_id, $letter_id) {
        $response = $this->client->comment_list($user_id, $letter_id);

        return json_decode($response, true);
    }

    public function __comment_create($user_id, $letter_id, $data) {

        $data_json = json_encode($data);
        try {
            return $this->client->comment_create($user_id, $letter_id, $data_json);
        } catch (letter\NotFoundError $e) {
            throw new NotFoundError($e->why);
        } catch (letter\JsonError $e) {
            throw new Exception($e->why);
        } catch (letter\InputValidationError $e) {
            throw new Exception($e->why);
        } catch (Exception $e) {
            pr(get_class($e));
            pr($e->getMessage());
        }

//        $code = $response->code;
//        $result = json_decode($response->result, true);
//
//        return array($code, $result);
    }

    public function __comment_remove($user_id, $letter_id, $comment_id) {
        return $this->client->comment_remove($user_id, $letter_id, $comment_id);
    }

    public function __user_create($user) {
        $json_user = json_encode($user);
        $response = $this->client->user_create($json_user);

        return $response;
    }

    public function __user_get($user_id) {
        $response = $this->client->user_get($user_id);

        return $response;
//        $response = $this->client->user_get($user_id);
//
//        return $response;
    }

    public function __call($method, $args) {
        $_method = '__' . $method;
        $response = null;

        if (!method_exists($this, $_method)) {
            throw new Exception(sprintf('%s: invalid method', $method));
        }

        try {
            $this->__initThrift();
            $this->transport->open();
            $response = call_user_func_array(array($this, $_method), $args);
            $this->transport->close();
        } catch (Thrift\Exception\TTransportException $e) {
            $this->transport->close();
//        } catch (NotFoundError $e) {
//            $this->transport->close();
//            throw $e;
//        } catch (ValidationError $e) {
//            $this->transport->close();
            throw $e;
        } catch (Exception $e) {
            $this->transport->close();
//            throw new Exception($e->getMessage());
            throw $e;
        }

        return $response;
    }

    public function __subscription_subscribe($actor_id, $target_id) {
        try {
            return $this->client->subscription_subscribe($actor_id, $target_id);
        } catch (\letter\InputValidationError $e) {
            throw new ValidationError($e->why);
        } catch (\letter\AlreadyExistsError $e) {
            throw new AlreadyExistsError($e->why);
        }
    }

    public function __subscription_unsubscribe($actor_id, $target_id) {
        try {
            return $this->client->subscription_unsubscribe($actor_id, $target_id);
        } catch (\letter\InputValidationError $e) {
            throw new ValidationError($e->why);
        } catch (\letter\NotFoundError $e) {
            throw new NotFoundError($e->why);
        }
    }

    public function __subscription_ids_subscribers($actor_id) {
        return $this->client->subscription_ids_subscribers($actor_id);
    }

    public function __subscription_ids_subscribings($actor_id) {
        return $this->client->subscription_ids_subscribings($actor_id);
    }

    public function __timeline_ids($user_id, $params = array()) {
        $params_json = empty($params) ? '{}' : json_encode($params);
        return $this->client->timeline_ids($user_id, $params_json);
    }

    public function __letters_get($user_id, $id) {
        $response = $this->client->letters_get($user_id, $id);
        $code = $response->code;
        if ($code == 200) {
            $result = json_decode($response->result, true);
        }

        return $result;
    }

    public function __letters_delete($user_id, $letter_id) {
        return $this->client->letters_delete($user_id, $letter_id);
    }

    public function __friendship_request_create($actor_id, $target_id) {
        try {
            return $this->client->friendship_request_create($actor_id, $target_id);
        } catch (\letter\InputValidationError $e) {
            throw new ValidationError($e->why);
        } catch (\letter\AlreadyExistsError $e) {
            throw new AlreadyExistsError($e->why);
        }
    }

    public function __friendship_request_cancel($actor_id, $target_id) {
        try {
            return $this->client->friendship_request_cancel($actor_id, $target_id);
        } catch (letter\InputValidationError $e) {
            throw new ValidationError($e->why);
        } catch (letter\NotFoundError $e) {
            throw new NotFoundError($e->why);
        }
    }

    public function __friendship_get_request_ids_to_user($actor_id) {
        $response = $this->client->friendship_get_request_ids_to_user($actor_id);
        return $response;
    }

    public function __friendship_get_request_ids_from_user($actor_id) {
        $response = $this->client->friendship_get_request_ids_from_user($actor_id);
        return $response;
    }

    public function __friendship_request_reject($actor_id, $target_id) {
        try {
            return $this->client->friendship_request_reject($actor_id, $target_id);
        } catch (\letter\InputValidationError $e) {
            throw new ValidationError($e->why);
        } catch (\letter\NotFoundError $e) {
            throw new NotFoundError($e->why);
        }
    }

    public function __friendship_request_accept($actor_id, $target_id) {
        try {
            return $this->client->friendship_request_accept($actor_id, $target_id);
        } catch (\letter\InputValidationError $e) {
            throw new ValidationError($e->why);
        } catch (\letter\NotFoundError $e) {
            throw new NotFoundError($e->why);
        }
    }

    public function __friendship_get_friend_ids($actor_id) {
        $response = $this->client->friendship_get_friend_ids($actor_id);
        return $response;
    }

    //20140707 by swoogi : get friends list of friends
    public function __friendship_get_friend_ids_of_friends($actor_id) {
        $response = $this->client->friendship_get_friend_ids_of_friends($actor_id);
        return $response;
    }

    public function __friendship_remove($actor_id, $target_id) {
        $response = $this->client->friendship_remove($actor_id, $target_id);
        return $response;
    }

    public function __timeline_items($user_id, $params = array()) {
        $params_json = empty($params) ? '{}' : json_encode($params);
        return $this->client->timeline_items($user_id, $params_json);
    }

    public function __notification_items($user_id, $params = array()) {
        $params_json = empty($params) ? '{}' : json_encode($params);
        return $this->client->notification_items($user_id, $params_json);
    }

    /**
     * @param $user_id
     *
     * array(
     *   array('type' => ..., 'item' => ..., 'key' => ...),
     *   ...
     * )
     */
    public function __timeline_all($user_id) {

    }

    public function __service_drop_collections() {
        return $this->client->service_drop_collections();
    }

    public function __letters_like_ids($letter_id) {
        return $this->client->letters_like_ids($letter_id);
    }

    public function __letters_like($user_id, $letter_id) {
        return $this->client->letters_like($user_id, $letter_id);
    }

    public function __letters_scrap($user_id, $letter_id) {
        return $this->client->letters_scrap($user_id, $letter_id);
    }

    public function __letters_unlike($user_id, $letter_id) {
        return $this->client->letters_unlike($user_id, $letter_id);
    }

    public function __letters_is_liked($user_id, $letter_id) {
        return $this->client->letters_is_liked($user_id, $letter_id);
    }

    public function __mailbox_inbox($user_id, $params = null, $filter = null) {
        $params_json = !empty($params) ? json_encode($params) : '{}';
        $filter_json = !empty($filter) ? json_encode($filter) : '{}';
        return $this->client->mailbox_inbox($user_id, $params_json, $filter_json);
    }

    public function __mailbox_timeline($user_id, $params = null) {
        $params_json = !empty($params) ? json_encode($params) : '{}';
        return $this->client->mailbox_timeline($user_id, $params_json);
    }

    public function __mailbox_scrap($user_id, $params = null, $filter = null) {
        $params_json = !empty($params) ? json_encode($params) : '{}';
        $filter_json = !empty($filter) ? json_encode($filter) : '{}';
        return $this->client->mailbox_scrap($user_id, $params_json, $filter_json);
    }

    public function __mailbox_scrap_delete($user_id, $letter_id) {
        return $this->client->mailbox_scrap_delete($user_id, $letter_id);
    }

    public function __mailbox_sent($user_id, $params = null) {
        $params_json = !empty($params) ? json_encode($params) : '{}';
        return $this->client->mailbox_sent($user_id, $params_json);
    }

    public function __mailbox_subscription($user_id, $params = null) {
        $params_json = !empty($params) ? json_encode($params) : '{}';
        return $this->client->mailbox_subscription($user_id, $params_json);
    }

    public function __mailbox_draft($user_id, $params = null, $filter = null) {
        $params_json = !empty($params) ? json_encode($params) : '{}';
        $filter_json = !empty($filter) ? json_encode($filter) : '{}';
        return $this->client->mailbox_draft($user_id, $params_json, $filter_json);
    }

    public function __mailbox_create($user_id, $mailbox_name, $type) {
        return $this->client->mailbox_create($user_id, $mailbox_name, $type);
    }

    public function __mailbox_list($user_id, $type) {
        return $this->client->mailbox_list($user_id, $type);
    }

    public function __mailbox_move_letter($user_id, $letter_id, $mailbox_id, $type) {
        return $this->client->mailbox_move_letter($user_id, $letter_id, $mailbox_id, $type);
    }

    public function __mailbox_move_letters($user_id, $letter_ids, $mailbox_id, $type) {
        return $this->client->mailbox_move_letter($user_id, $letter_ids, $mailbox_id, $type);
    }

}

class ApiException extends Exception {
}

class NotFoundError extends Exception {
}

class ValidationError extends Exception {
}

class AlreadyExistsError extends Exception {
}
