<?php

require_once('../controllers/DataBase.php');
require_once('../lib/base.php');

class DataBaseTest extends PHPUnit_Framework_TestCase {
    public function setUp() {}
    public function tearDown() {}

    public function testConnect() {
       $connection = new DataBase();
       $this->assertTrue($connection->connect() !== false);
    }
}
