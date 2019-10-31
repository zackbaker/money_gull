<?php

class TransactionController extends ApiController
{
    private $Account;

    public function __construct()
    {
        parent::__construct();
        $this->Goal = new GoalModel();
        $this->Account = new AccountModel();
        $this->Transaction = new TransactionModel();
    }

    public function saveAccountTransaction($f3)
    {
        $userId = $f3->get('PARAMS.userId');
        $accountId = $f3->get('PARAMS.accountId');

        try {
            $this->Account->checkForExistingAccount(
                $userId,
                $accountId
            );
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }

        $saveData = $this->getPost();
        $saveData = $this->Transaction->sanatizeData($saveData);

        switch ($saveData['type']) {
        	case 'expense':
        		$saveData['Accounts_from'] = $accountId;
        		break;

        	case 'income':
        		$saveData['Accounts_to'] = $accountId;
        		break;
        }

        $saveData = $this->stageData($saveData, $userId);

        $this->Transaction->saveTransaction($saveData);

        try {
            echo $this->Transaction->getLastTransaction($userId);
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }

    public function saveGoalTransaction($f3)
    {
        $userId = $f3->get('PARAMS.userId');
        $goalId = $f3->get('PARAMS.goalId');

        try {
            $this->Goal->checkForExistingGoal($userId, $goalId);
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }

        $saveData = $this->getPost();
        $saveData = $this->Transaction->sanatizeData($saveData);
        $saveData['Goals_to'] = $goalId;
        $saveData = $this->stageData($saveData, $userId);

        $this->Transaction->saveTransaction($saveData);

        try {
            echo $this->Transaction->getLastTransaction($userId);
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }

    public function deleteTransaction($f3)
    {
    	try {
            $this->Transaction->delete(
                $f3->get('PARAMS.userId'),
                $f3->get('PARAMS.transactionId')
            );

            echo $this->sendSucessMessage(
                200,
                'Deletion Successful'
            );
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                403,
                $e->getMessage()
            );
        }
    }

    public function updateTransaction($f3)
    {
    	$userId = $f3->get('PARAMS.userId');
    	$saveData = $this->Transaction->sanatizeData($_GET);
    	$saveData = $this->stageData($saveData, $userId);

    	try {
    		echo $this->Transaction->update(
    			$userId,
    			$f3->get('PARAMS.transactionId'),
    			$saveData
    		);
    	} catch (Exception $e) {
    		echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
    	}
    }

    public function getAllTransactions($f3)
    {
        try {
            echo $this->Transaction->getAllTransactions(
                $f3->get('PARAMS.userId')
            );
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }

    private function stageData($saveData, $userId)
    {
        $mappedData = [];

        foreach ($saveData as $column => $value) {
            switch ($column) {
                case 'date':
                    $mappedData['date_time'] = $value;
                    break;

                case 'description':
                    $mappedData['summary'] = $value;
                    break;

                case 'account_id':
                    $mappedData['Accounts_from'] = $value;
                    break;

                default:
                    $mappedData[$column] = $value;
                    break;
            }
        }

        $mappedData['Users_id'] = $userId;

        return $mappedData;
    }
}