<?php

use League\Fractal;

class AccountModel extends ApiModel
{
    private $transformer = 'AccountTransformer';
    protected $pageCount = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveAccount($userId, $accountInfo)
    {
        $this->db->exec('
            INSERT INTO Accounts (Users_id, name, amount)
            VALUES (' .
                $userId . ',
                "' . $accountInfo['account_name'] . '", ' .
                $accountInfo['account_amount'] .
            ')
        ');
    }

    public function getLastAccount($userId)
    {
        $account = [];
        $account['data'] = $this->db->exec('
            SELECT id, name, amount
            FROM Accounts
            WHERE Users_id = ' . $userId . '
            ORDER BY id DESC
            LIMIT 1
        ');

        if (empty($account['data'])) {
            throw new Exception('No Accounts for this user');
        } else {
            $account['transformer'] = $this->transformer;
            return $this->createTransformer($account);
        }

    }

    public function delete($userId, $accountId)
    {
        $accountCheck = $this->checkForExistingAccount($userId, $accountId);

        if (!empty($accountCheck)) {
            $this->db->exec('
                DELETE
                FROM Accounts
                WHERE Users_id = ' . $userId . '
                    AND id = ' . $accountId
            );

            return true;
        }

    }

    public function getAllAccounts($userId, $pageNum)
    {
        $accounts = [];
        $accounts['data'] = $this->db->exec('
            SELECT id, name, amount
            FROM Accounts
            WHERE Users_id = ' . $userId . '
            LIMIT ' . ($pageNum - 1) * $this->pageCount . ', ' . $this->pageCount
        );

        $pagination = $this->getPagination(
            $userId,
            'accounts',
            [
                'pageNum' => $pageNum,
                'totalCount' => $this->getTotalAccounts($userId),
            ]
        );

        if (empty($accounts['data'])) {
            throw new Exception('No Accounts found');
        } else {
            $accounts['transformer'] = $this->transformer;
            return $this->createTransformer($accounts, [], $pagination);
        }
    }

    public function getSingleAccount($userId, $accountId, $transactionMonth)
    {
        $this->checkForExistingAccount($userId, $accountId);

        $account = [];
        $account['transformer'] = $this->transformer;
        $account['data'] = $this->db->exec('
            SELECT id, name, amount
            FROM Accounts
            WHERE id = ' . $accountId
        );

        $TransactionsModel = new TransactionModel();

        $transactions = $TransactionsModel->getAllAccountTransactions(
            $accountId,
            $transactionMonth
        );

        return $this->createTransformer(
            $account,
            [
                'TransactionTransformer' => $transactions,
            ]
        );
    }

    public function update($userId, $accountId, $accountUpdate)
    {
        $this->checkForExistingAccount($userId, $accountId);

        $this->db->exec('
            UPDATE Accounts
            SET name = "' . $accountUpdate['account_name'] . '",
                amount = "' . $accountUpdate['account_amount'] . '"
            WHERE id = ' . $accountId
        );

        $updatedAccount = [];
        $updatedAccount['transformer'] = $this->transformer;
        $updatedAccount['data'] = $this->db->exec('
            SELECT id, name, amount
            FROM Accounts
            WHERE id = ' . $accountId
        );

        return $this->createTransformer($updatedAccount);
    }

    public function checkForExistingAccount($userId, $accountId)
    {
        $account = $this->db->exec('
            SELECT id
            FROM Accounts
            WHERE Users_id = ' . $userId . '
                AND id = ' . $accountId
        );

        if (empty($account)) {
            throw new Exception('Sorry, this account does not exist');
        }

        return $account;
    }

    private function getTotalAccounts($userId)
    {
        $total = $this->db->exec('
            SELECT COUNT(id) AS count
            FROM Accounts
            WHERE Users_id = ' . $userId
        )[0];

        return $total['count'];
    }
}
