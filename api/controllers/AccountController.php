<?php

class AccountController extends ApiController
{
    private $Account;

    public function __construct()
    {
        parent::__construct();
        $this->Account = new AccountModel();
    }

    public function saveAccount($f3)
    {
        $accountInfo = $this->getPOST();
        $accountInfo = $this->Account->sanatizeData($accountInfo);
        $this->Account->saveAccount($f3->get('PARAMS.userId'), $accountInfo);

        try {
            echo $this->Account->getLastAccount($f3->get('PARAMS.userId'));
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }

    public function deleteAccount($f3)
    {
        try {
            $this->Account->delete(
                $f3->get('PARAMS.userId'),
                $f3->get('PARAMS.accountId')
            );

            echo $this->sendSucessMessage(
                200,
                'Deletion Successful'
            );
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }

    public function getAllAccounts($f3)
    {
        if (!empty($_GET)) {
            $pageNum = $this->Account->sanatizeData($_GET);
            $pageNum = $pageNum['page_num'];
        } else {
            $pageNum = 1;
        }

        try {
            echo $this->Account->getAllAccounts(
                $f3->get('PARAMS.userId'),
                $pageNum
            );
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }

    public function getSingleAccount($f3)
    {
        $transactions = $this->Account->sanatizeData($_GET);

        if (empty($transactions['month'])) {
            $transactions['month'] = date('Y-m-01');
        }

        try {
            echo $this->Account->getSingleAccount(
                $f3->get('PARAMS.userId'),
                $f3->get('PARAMS.accountId'),
                $transactions['month']
            );
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }

    public function updateAccount($f3)
    {
        $accountUpdate = $this->Account->sanatizeData($_GET);

        try {
            echo $this->Account->update(
                $f3->get('PARAMS.userId'),
                $f3->get('PARAMS.accountId'),
                $accountUpdate
            );
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                403,
                $e->getMessage()
            );
        }
    }
}
