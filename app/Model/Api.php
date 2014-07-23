<?php
require_once APP . 'Vendor/lib/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = APP . 'Vendor/gen-php';
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', APP . "Vendor/lib");
$loader->registerDefinition('shared', $GEN_DIR);
$loader->registerDefinition('letter', $GEN_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TFramedTransport;
use Thrift\Exception\TException;

class Api extends AppModel {

    public $client = null;
    public $useTable = false;

    private function __initThrift() {
        $backend = Configure::read('Backend');

        if (!is_null($this->client)) {
            return true;
        }

        try {
            $socket = new TSocket($backend['hostname'], $backend['port']);
            $this->transport = new TFramedTransport($socket, 1024, 1024);
            $this->protocol = new TBinaryProtocol($this->transport);
            $this->client = new \letter\LetterApiServiceClient($this->protocol);

        } catch (TException $tx) {
            print 'TException: '.$tx->getMessage()."\n";
        }

    }

    public function ping() {
        $this->__initThrift();
        $this->transport->open();
        $response = $this->client->ping();
        $this->transport->close();

        return array($response->code, json_decode($response->result, true));
    }

}

