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
$loader->registerDefinition('today', $GEN_DIR);
$loader->register();

/**
 * @property TFramedTransport transport
 * @property TBinaryProtocol protocol
 * @property TodayInternalApiServiceClient client
 */
class TodayApi extends AppModel {

    public $client = null;
    public $useTable = false;
    public $connection = 'default';

    private function __initThrift() {
        $backend = Configure::read('Backend');

        if (!is_null($this->client)) {
            return true;
        }

        try {
            $socket = new TSocket($backend[$this->connection]['hostname'], $backend[$this->connection]['port']);
            $socket->setRecvTimeout(10000);
            $this->transport = new TFramedTransport($socket, 1024, 1024);
            $this->protocol = new TBinaryProtocol($this->transport);
            $this->client = new \today\TodayInternalApiServiceClient($this->protocol);
        } catch (TException $tx) {
            throw $tx;
        }

    }

    public function __call($method, $args) {
//        $_method = '__' . $method;
        $response = null;

//        if (!method_exists($this, $_method)) {
//            throw new Exception(sprintf('%s: invalid method', $method));
//        }

        try {
            $this->__initThrift();
            $this->transport->open();
//            $response = call_user_func_array(array($this, $_method), $args);
            $response = call_user_func_array(array($this->client, $method), $args);
            $this->transport->close();
        } catch (Thrift\Exception\TTransportException $e) {
            $this->transport->close();
            throw $e;
        } catch (Exception $e) {
            $this->transport->close();
            throw $e;
        }

        return $response;
    }

}
