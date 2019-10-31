<?php

require_once('../lib/base.php');
require_once('../controllers/DataBase.php');
require_once('../controllers/User.php');

class UserTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->User = new User;
    }

    public function tearDown() {
    }

    public function testsignUpSucess() {
        $this->User->dataIn = '/tmp/data.dat';
        $testInfo = array(
            'userName' => 'testing',
            'email' => 'testing@testing.com',
            'password' => 'Testing1',
        );
        
        file_put_contents('/tmp/data.dat', json_encode($testInfo));
        $this->assertTrue($this->User->signUp() !== false);
        unlink($this->User->dataIn);
    }

    public function testsignUpFailureUserName() {
        $this->User->dataIn = '/tmp/data.dat';
        $testInfo = array(
            'userName' => 'testing',
            'email' => 'testing@testingg.com',
            'password' => 'Testing1',
        );

        file_put_contents('/tmp/data.dat', json_encode($testInfo));
        ob_start();
        $this->User->signUp();
        
        $ret = ob_get_clean();
        $ret = json_decode($ret, true);
        
        $this->assertContains('userName', $ret['errors']);
        unlink($this->User->dataIn);
    }

    public function testsignUpFailureEmail() {
        $this->User->dataIn = '/tmp/data.dat';
        $testInfo = array(
            'userName' => 'testingg',
            'email' => 'testing@testing.com',
            'password' => 'Testing1',
        );

        file_put_contents('/tmp/data.dat', json_encode($testInfo));
        ob_start();
        
        $this->User->signUp();
        
        $ret = ob_get_clean();
        $ret = json_decode($ret, true);
        
        $this->assertContains('email', $ret['errors']);
        unlink($this->User->dataIn);
    }

    public function testSignInSuccess() {
        $this->User->dataIn = '/tmp/data.dat';
        $testInfo = array(
            'userName' => 'testing',
            'email' => 'testing@testing.com',
            'password' => 'Testing1',
        );

        file_put_contents('/tmp/data.dat', json_encode($testInfo));
        $this->assertTrue($this->User->signIn() !== false);
        unlink($this->User->dataIn);    
    }

    public function testsignInFailureNoUser() {
        $this->User->dataIn = '/tmp/data.dat';
        $testInfo = array(
            'userName' => 'testing',
            'email' => 'testing@testingg.com',
            'password' => 'Testing1',
        );

        file_put_contents('/tmp/data.dat', json_encode($testInfo));
        ob_start();
        
        $this->User->signIn();
        
        $ret = ob_get_clean();
        $ret = json_decode($ret, true);
        
        $this->assertContains('noUser', $ret['errors']);
        unlink($this->User->dataIn);        
    }

    public function testsignInFailureIncorrectPassword() {
        $this->User->dataIn = '/tmp/data.dat';
        $testInfo = array(
            'userName' => 'testing',
            'email' => 'testing@testing.com',
            'password' => 'Testing11',
        );

        file_put_contents('/tmp/data.dat', json_encode($testInfo));
        ob_start();
        
        $this->User->signIn();
        
        $ret = ob_get_clean();
        $ret = json_decode($ret, true);
        
        $this->assertContains('incorrectPassword', $ret['errors']);
        unlink($this->User->dataIn);        
    }

    public function testdeleteUser() {
        $this->User->deleteUser('testing@testing.com', 'testing');
    }
}
