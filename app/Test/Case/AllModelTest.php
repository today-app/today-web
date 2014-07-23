<?php
/**
 * Created by PhpStorm.
 * User: yoophi
 * Date: 4/24/14
 * Time: 4:44 PM
 */
class AllModelTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All model tests');
        $suite->addTestDirectory(TESTS . 'Case/Model');
        return $suite;
    }
}