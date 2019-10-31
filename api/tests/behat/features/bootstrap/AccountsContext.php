<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class AccountsContext extends FeatureContext implements Context
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
        if (strpos($uri, ':lastAccountId') !== false) {
            $this->uri = str_replace(':lastAccountId', $this->findLastAccountId(), $uri);            
        } else {
            $this->uri = $uri;
        }

        $this->method = $method;
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
            !empty($this->response['data']['accounts']['id']) &&
            !is_int($this->response['data']['accounts']['id'])
        ) {
            throw new Exception(
                'returned id should be an integer not ' . gettype($this->response['data']['accounts']['id'])
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
     * @Then the response code will be :responsecode
     */
    public function theResponseCodeWillBe($responsecode)
    {
        if ($responsecode != $this->responseStatus) {
            throw new Exception(
                'Response Status should be ' . $responsecode . ' Instead was ' . $this->responseStatus
            );
            
        }
    }

    /**
     * @Given we use the method :method and the uri :uri
     */
    public function weUseTheMethodAndTheUri($method, $uri)
    {
        $this->method = $method;
        $this->uri = $uri;
    }

    /**
     * @When we pass name as :account and amount as :amount
     */
    public function wePassNameAsAndAmountAs($account, $amount)
    {
        $this->sendData = [
            'account' => $account,
            'amount' => $amount,
        ];
    }

    /**
     * @Then transactions properties will be:
     */
    public function transactionsPropertiesWillBe(PyStringNode $properties)
    {
        $properties = explode(PHP_EOL, $properties);
        $diff = array_diff($properties, array_keys($this->response['data']['transactions'][0]));
        
        if (!empty($diff)) {
            throw new Exception(
                'missing the following properties: ' . var_dump($diff)
            );
        }
    }

    /**
     * @Then the accountId will be an integer
     */
    public function theAccountidWillBeAnInteger()
    {
        if (!is_int($this->response['data']['transactions'][0]['id'])) {
            throw new Exception(
                'Expecting Account id to be an integer but was instead' . gettype($this->response['data']['transactions'][0]['id'])
            );
            
        }
    }

    /**
     * @Then the transactionId will be an integer
     */
    public function theTransactionidWillBeAnInteger()
    {
        if (!is_int($this->response['data']['transactions'][0]['id'])) {
            throw new Exception(
                'Expecting Transaction id to be an integer but was instead' . gettype($this->response['data']['transactions'][0]['id'])
            );
            
        }
    }

    /**
     * @When they pass through accountName as :account and accountAmount as :amount
     */
    public function theyPassThroughAccountnameAsAndAccountamountAs2($account, $amount)
    {
        $this->sendData = [
            'account_name' => $account,
            'account_amount' => $amount,
        ];
    }
}
