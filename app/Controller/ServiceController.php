<?php
/**
 * @property mixed Api
 */
class ServiceController extends AppController {

    public $uses = array('Api');

    function index() {
        $this->setAction('status');
    }

    function status() {
        list($code, $result) = $this->Api->ping();
        $this->set('result', $result);
    }
}
