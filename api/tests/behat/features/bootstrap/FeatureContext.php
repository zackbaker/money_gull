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
class FeatureContext implements Context {
    protected $client;
    protected $connection;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->client = new GuzzleHttp\Client(
            [
                'http_errors' => false,
                'base_uri' => 'http://api.moneygull.local',
            ]
        );

        $this->connection = new DB\SQL(
            'mysql:host=localhost;port=3306;dbname=money_gull',
            'root',
            'money_gull'
        );
    }

    protected function apiCall($method, $uri, $data = [])
    {
        try {
            if (empty($data)) {
                $ret = $this->client->request($method, $uri);
            } else {
                switch (strtolower($method)) {
                    case 'post':
                        $sendData = ['json' => $data];
                        break;

                    default:
                        $sendData = ['query' => $data];
                        break;
                }

                $ret = $this->client->request(
                    $method,
                    $uri,
                    $sendData
                );
            }
        } catch (RequestException $e) {
            echo $e->getRequest();

            if ($e->hasResponse()) {
                echo $e->hasResponse();
            }
        }

        return $ret;
    }

    protected function findLastUserId() {
        return $this->connection->exec('
            SELECT max(id) AS id
            FROM Users
        ');
    }

    protected function findLastAccountId()
    {
        $accountId = $this->connection->exec('
            SELECT MAX(id) AS id
            FROM Accounts
        ')[0];

        return $accountId['id'];
    }

    protected function findLastGoalId()
    {
        $goalId = $this->connection->exec('
            SELECT MAX(id) AS id
            FROM Goals
        ')[0];

        return $goalId['id'];
    }

    protected function findLastTransactionId()
    {
        $transactionId = $this->connection->exec('
            SELECT MAX(id) AS id
            FROM Transactions
        ')[0];

        return $transactionId['id'];
    }
}
