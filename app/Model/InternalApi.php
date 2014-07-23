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
$loader->registerDefinition('social', $GEN_DIR);
$loader->register();

/**
 * @property TFramedTransport transport
 * @property TBinaryProtocol protocol
 * @property LetterApiServiceClient client
 */
class InternalApi extends AppModel {

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
//            $this->client = new \letter\LetterApiServiceClient($this->protocol);
            $this->client = new \social\InternalApiServiceClient($this->protocol);

        } catch (TException $tx) {
            print 'TException: ' . $tx->getMessage() . "\n";
            pr($tx);
        } catch (Exception $e) {
            pr($e);
        }

    }

    public function __call($method, $args) {
        $response = null;

        try {
            $this->__initThrift();
            $this->transport->open();
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
