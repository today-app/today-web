<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/cakeunit4phpstorm.php');
App::uses('TodayApi', 'Model');

/**
 * TodayApi Test Case
 *
 * @property TodayApi TodayApi
 */
class TodayApiTest extends CakeTestCase {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array();

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->TodayApi = ClassRegistry::init('TodayApi');
        $this->faker = Faker\Factory::create();

        $this->TodayApi->system_reset_fixtures();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown() {
        unset($this->TodayApi);
        unset($this->faker);

        parent::tearDown();
    }

    /**
     * testGet method
     *
     * @return void
     */
    public function testPostCreate() {
        $user_id = 1;
        $this->assertEqual(1, $this->TodayApi->post_create($user_id, 'hello'));
    }

}
