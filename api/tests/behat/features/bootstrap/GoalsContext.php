<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class GoalsContext Extends FeatureContext implements Context
{
    private $method;
    private $uri;
    private $sendData;
    private $guzzleObj;
    private $response;
    private $responseStatus;

    /**
     * @Given we use the method :method and uri :uri
     */
    public function weUseTheMethodAndUri($method, $uri)
    {
        if (strpos($uri, ':lastGoalsId') !== false) {
            $uri = str_replace(':lastGoalsId', $this->findLastGoalId(), $uri);
        }

        $this->uri = $uri;
        $this->method = $method;
    }

    /**
     * @When they pass through name as :name, needed as :amountNeeded, saved as :amountSaved
     */
    public function theyPassThroughNameAsNeededAsSavedAs($name, $amountNeeded, $amountSaved)
    {
        $this->sendData = [
            'goal_name' => $name,
            'amount_needed' => $amountNeeded,
            'amount_saved' => $amountSaved,
        ];
    }

    /**
     * @Then we will check it against the database and send back info in the scope of :scope
     */
    public function weWillCheckItAgainstTheDatabaseAndSendBackInfoInTheScopeOf($scope)
    {
        $this->guzzleObj = $this->apiCall($this->method, $this->uri, $this->sendData);
        $this->response = json_decode($this->guzzleObj->getBody(), true);
        $this->responseStatus = $this->guzzleObj->getStatusCode();

        if (empty($this->response[$scope])) {
            throw new Exception('
                Expecting the scope to be ' . $scope . '
                Instead returned: ' . var_dump($this->response)
            );   
        }
    }

    /**
     * @Then the properties would be:
     */
    public function thePropertiesWouldBe(PyStringNode $properties)
    {
        $diff = '';
        $properties = explode(PHP_EOL, $properties);
        
        if (!empty($this->response['data']['accounts'][0])) {
            $diff = array_diff($properties, array_keys($this->response['data']['accounts'][0]));
        }
        
        if (!empty($diff)) {
            throw new Exception(
                'missing the following properties: ' . var_dump($diff)
            );
        }
    }

    /**
     * @Then the id will be an integer
     */
    public function theIdWillBeAnInteger()
    {
        if (
            !empty($this->response['data']['goals']['id']) &&
            !is_int($this->response['data']['goals']['id'])
        ) {
            throw new Exception(
                'returned id should be an integer not ' . gettype($this->response['data']['accounts']['id'])
            );
        }
    }

    /**
     * @Then there will be a status code of :statusCode
     */
    public function thereWillBeAStatusCodeOf($statusCode)
    {
        if ($this->responseStatus != $statusCode) {
            throw new Exception(
                'Status code should be ' . $statusCode . ' not ' . $this->responseStatus
            );
        }
    }

    /**
     * @Then we check it against the database and send back info in the scope of :scope
     */
    public function weCheckItAgainstTheDatabaseAndSendBackInfoInTheScopeOf($scope)
    {
        $this->guzzleObj = $this->apiCall($this->method, $this->uri, $this->sendData);
        $this->response = json_decode($this->guzzleObj->getBody(), true);
        $this->responseStatus = $this->guzzleObj->getStatusCode();

        if (empty($this->response[$scope])) {
            throw new Exception(
                'Scope should be ' . $scope . ' But the following was returned: ' . var_dump($this->response)
            );
        }
    }

    /**
     * @Then the property action will say :message
     */
    public function thePropertyActionWillSay($message)
    {
        if ($this->response['data']['message'] != $message) {
            throw new Exception(
                'Wrong message returned was expecting: ' . $message .
                ' Received: ' . $this->response['date']['message']
            );
        }
    }

    /**
     * @Then we will return a error in the scoped :scope
     */
    public function weWillReturnAErrorInTheScoped($scope)
    {
        $this->guzzleObj = $this->apiCall($this->method, $this->uri, $this->sendData);
        $this->response = json_decode($this->guzzleObj->getBody(), true);
        $this->responseStatus = $this->guzzleObj->getStatusCode();

        if (empty($this->response[$scope])) {
            throw new Exception(
                'Expecting scope ' . $scope . ' instead got ' . var_dump($this->response)
            );
        }
    }

    /**
     * @Then the property message will be :message
     */
    public function thePropertyMessageWillBe($message)
    {
        if ($this->response['error']['message'] != $message) {
            throw new Exception(
                'Error message should be ' . $message . ', ' . $this->response['error']['message'] . ' was returned'
            );
        }
    }

    /**
     * @Then pagination properties will be:
     */
    public function paginationPropertiesWillBe(PyStringNode $properties)
    {
        $properties = explode(PHP_EOL, $properties);
        $diff = array_diff($properties, array_keys($this->response['meta']));

        if (!empty($diff)) {
            throw new Exception(
                'missing the following properties: ' . var_dump($diff)
            );
        }
    }

    /**
     * @Then the response code will be :arg1
     */
    public function theResponseCodeWillBe($statusCode)
    {
        if ($this->responseStatus != $statusCode) {
            throw new Exception(
                'Status code should be ' . $statusCode . ' not ' . $this->responseStatus
            );
        }
    }

    /**
     * @When we pass name as :name, needed as :needed, and saved as :saved
     */
    public function wePassNameAsNeededAsAndSavedAs($name, $needed, $saved)
    {
        $this->sendData = [
            'name' => $name,
            'needed' => $needed,
            'saved' => $saved,
        ];
    }

    /**
     * @Then transactions properties will be:
     */
    public function transactionsPropertiesWillBe(PyStringNode $string)
    {
        $diff = '';
        $properties = explode(PHP_EOL, $properties);
        
        if (!empty($this->response['data']['accounts'][0]['transactions'])) {
            $diff = array_diff($properties, array_keys($this->response['data']['accounts'][0]));
        }
        
        if (!empty($diff)) {
            throw new Exception(
                'missing the following properties: ' . var_dump($diff)
            );
        }
    }

    /**
     * @Then the goalId will be an integer
     */
    public function theGoalidWillBeAnInteger()
    {
        if (
            !empty($this->response['data']['id']) &&
            !is_int($this->response['data']['id'])
        ) {
            throw new Exception(
                'returned id should be an integer not ' .
                gettype($this->response['data']['goals'][0]['transactions'][0]['id'])
            );
        }
    }

    /**
     * @Then the transactionId will be an integer
     */
    public function theTransactionidWillBeAnInteger()
    {
        if (
            !empty($this->response['data']['goals']['transactions'][0]['id']) &&
            !is_int($this->response['data']['goals']['transactions'][0]['id'])
        ) {
            throw new Exception(
                'returned id should be an integer not ' .
                gettype($this->response['data']['goals'][0]['transactions'][0]['id'])
            );
        }
    }
}
