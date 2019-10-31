<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Context;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

/**
 * Defines application features from the specific context.
 */
class UsersContext extends FeatureContext implements Context {
    private $method;
    private $uri;
    private $sendData;
    private $guzzleObj;
    private $response;
    private $responseStatus;

    /**
     * @Given we use the method :method and uri :uri
     */
    public function weUseTheMethodAndUri($method, $uri) {
        $this->method = $method;
        $this->uri = $uri;
    }

    /**
     * @When they pass through email as :email, password as :password, and user name as :name
     */
    public function theyPassThroughEmailAsPasswordAsAndUserNameAs($email, $password, $name) {
        $this->sendData = [
            'email' => $email,
            'password' => $password,
            'name' => $name,
        ];

        $this->guzzleObj = $this->apiCall($this->method, $this->uri, $this->sendData);
        $this->response = json_decode($this->guzzleObj->getBody(), true);
        $this->responseStatus = $this->guzzleObj->getStatusCode();
    }

    /**
     * @Then we will check it against the database and send back info in the scope of :data
     */
    public function weWillCheckItAgainstTheDatabaseAndSendBackInfoInTheScopeOf($data) {
        if (empty($this->response[$data])) {
            throw new Exception('
                Scope is not what was expected which is ' . $data .
                ' Returned was: ' . var_dump($this->response)
            );
        }
    }

    /**
     * @Then the properties would be:
     */
    public function thePropertiesWouldBe(PyStringNode $properties) {
        $properties = explode(PHP_EOL, $properties);
        $diff = array_diff($properties, array_keys($this->response['data']));
        
        if (!empty($diff)) {
            throw new Exception('
                missing the following properties: ' . var_dump($diff)
            );
        } 
    }

    /**
     * @Then the id will be an integer
     */
    public function theIdWillBeAnInteger() {
        if (!is_int($this->response['data']['id'])) {
            throw new Exception('
                id is a ' . gettype($this->response['data']['id'])
            );
        }
    }

    /**
     * @Then we will return a error in the scoped :error
     */
    public function weWillReturnAErrorInTheScoped($error) {
        if (empty($this->response[$error])) {
            throw new Exception('Was expecting a scope of ' . $error);
        }
    }

    /**
     * @Then the property message will be :errorMessage
     */
    public function thePropertyMessageWillBe($errorMessage) {
        if ($this->response['error']['message'] != $errorMessage) {
            throw new Exception('
                Was expecting ' . $errorMessage .
                ' and recieved ' . $this->response['error']['message']
            );
        }
    }

    /**
     * @Then there will be a status code of :statusCode
     */
    public function thereWillBeAStatusCodeOf($statusCode) {
        if ($this->responseStatus != $statusCode) {
            throw new Exception('
                expecting status code ' . $statusCode .
                ' recieved status code ' . $this->responseStatus
            );
        }
    }

    /**
     * @When they type in their email as :email and password as :password
     */
    public function theyTypeInTheirEmailAsAndPasswordAs($email, $password) {
        $this->sendData = [
            'email' => $email,
            'password' => $password,
        ];

        $this->guzzleObj = $this->apiCall($this->method, $this->uri, $this->sendData);
        $this->response = json_decode($this->guzzleObj->getBody(), true);
        $this->responseStatus = $this->guzzleObj->getStatusCode();
    }

    /**
     * @Then the usersId will be an integer
     */
    public function theUsersidWillBeAnInteger() {
        if (!is_int($this->response['data']['id'])) {
            throw new Exception('
                id is a ' . gettype($this->response['data']['id'])
            );
        }
    }

    /**
     * @When they type in their email as :email and password as :password for deletion
     */
    public function theyTypeInTheirEmailAsAndPasswordAsForDeletion($email, $password) {
        $this->sendData = [
            'email' => $email,
            'password' => $password,
        ];

        // Finding out the last user id to 
        // delete it so further testing is possible
        $deletionId = $this->findLastUserId();
        $deletionId = $deletionId[0]['id'];

        $this->guzzleObj = $this->apiCall(
            $this->method,
            $this->uri . '/' . $deletionId,
            $this->sendData
        );

        $this->response = json_decode($this->guzzleObj->getBody(), true);
    }

    /**
     * @Then we will return :bool for deletion of user.
     */
    public function weWillReturnAnForDeletionOfUser($bool) {
        if ($this->response != $bool) {
            throw new Exception(
                'response should be ' . $bool . ' And instead is: ' . $this->response
            );
            
        }
    }
}
