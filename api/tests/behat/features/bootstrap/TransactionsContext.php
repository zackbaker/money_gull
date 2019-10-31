<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class TransactionsContext extends FeatureContext implements Context
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
        if (strpos($uri, ':lastTransactionsId') !== false) {
            $this->uri = str_replace(':lastTransactionsId', $this->findLastTransactionId(), $uri);            
        } else {
            $this->uri = $uri;
        }

        $this->method = $method;
    }

    /**
     * @When they pass through type as :type, date as :date, description as :description
     */
    public function theyPassThroughTypeAsDateAsDescriptionAs($type, $date, $description)
    {
        if ($date === 'NOW') {
            $date = date('Y-m-d H:i:s');
        }

        $this->sendData = [
            'type' => $type,
            'date' => $date,
            'description' => $description,
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

        if (!empty($this->response['data'])) {
            $diff = array_diff($properties, array_keys($this->response['data']));
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
            !empty($this->response['data']['transactions']['id']) &&
            !is_int($this->response['data']['transactions']['id'])
        ) {
            throw new Exception(
                'returned id should be an integer not ' . gettype($this->response['data']['transactions']['id'])
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
     * @When they pass through type as :type, amount as :amount, date as :date, and description as :description
     */
    public function theyPassThroughTypeAsAmountAsDateAsAndDescriptionAs($type, $amount, $date, $description)
    {
        if ($date === 'NOW') {
            $date = date('Y-m-d H:i:s');
        }

        $this->sendData = [
            'type' => $type,
            'amount' => $amount,
            'date' => $date,
            'description' => $description,
        ];
    }

    /**
     * @Then the transactionId will be an integer
     */
    public function theTransactionidWillBeAnInteger()
    {
        if (
            !empty($this->response['data']['transactions']['id']) &&
            !is_int($this->response['data']['transactions']['id'])
        ) {
            throw new Exception(
                'returned id should be an integer not ' . gettype($this->response['data']['transactions']['id'])
            );
        }
    }

    /**
     * @Then the AccountFromId will be an integer
     */
    public function theAccountfromidWillBeAnInteger()
    {
        if (
            !empty($this->response['data']['transactions']['Accounts_from']) &&
            !is_int($this->response['data']['transactions']['Accounts_from'])
        ) {
            throw new Exception(
                'returned id should be an integer not ' . gettype($this->response['data']['transactions']['Accounts_from'])
            );
        }
    }

    /**
     * @Then the GoalId will be an integer
     */
    public function theGoalidWillBeAnInteger()
    {
        if (
            !empty($this->response['data']['transactions']['Goals_to']) &&
            !is_int($this->response['data']['transactions']['Goals_to'])
        ) {
            throw new Exception(
                'returned id should be an integer not ' . gettype($this->response['data']['transactions']['Goals_to'])
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
                'Error message should be ' . $message . ', ' . $this->response['data']['message'] . ' was returned'
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
            throw new Exception('
                Expecting the scope to be ' . $scope . '
                Instead returned: ' . var_dump($this->response)
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
     * @When they pass amount as :amount and description as :description
     */
    public function theyPassAmountAsAndDescriptionAs($amount, $description)
    {
        $this->sendData = [
            'amount' => $amount,
            'description' => $description,
        ];
    }

    /**
     * @When they pass through account_id as :accountId, type as :type, amount as :amount, date as :date, description as :description
     */
    public function theyPassThroughAccountIdAsTypeAsAmountAsDateAsDescriptionAs2($accountId, $type, $amount, $date, $description)
    {
        if ($date === 'NOW') {
            $date = date('Y-m-d H:i:s');
        }

        $this->sendData = [
            'account_id' => $accountId,
            'type' => $type,
            'amount' => $amount,
            'date' => $date,
            'description' => $description,
        ];
    }
}
