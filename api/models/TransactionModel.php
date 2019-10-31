<?php

use League\Fractal;

class TransactionModel extends ApiModel
{
    private $transformer = 'TransactionTransformer';
    protected $pageCount = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveTransaction($saveData)
    {
        $this->db->exec('
            INSERT INTO Transactions (' . implode(', ', array_keys($saveData)) . ')
            VALUES ("' . implode('", "', $saveData) . '")
        ');
    }

    public function getLastTransaction($userId)
    {
        $transaction = [];
        $transaction['transformer'] = $this->transformer;
        $transaction['data'] = $this->db->exec('
            SELECT
                id,
                Accounts_from,
                Accounts_to,
                Goals_to,
                amount,
                type,
                date_time,
                summary
            FROM Transactions
            WHERE Users_id = ' . $userId . '
            ORDER BY id DESC
            LIMIT 1
        ');

        if (empty($transaction['data'])) {
            throw new Exception('No Transactions Exist');
        }

        return $this->createTransformer($transaction);
    }

    public function update($userId, $transactionId, $saveData)
    {
        $transaction = $this->getTransaction($userId, $transactionId);

        if (empty($transaction)) {
            throw new Exception('Transaction does not exist');
        }

        $save = $this->configureSet($saveData);

        $this->db->exec('
            UPDATE Transactions
            SET ' . $save . '
            WHERE id = ' . $transactionId
        );

        $transaction = [];
        $transaction['transformer'] = $this->transformer;
        $transaction['data'] = $this->getTransaction($userId, $transactionId);

        return $this->createTransformer($transaction);
    }

    public function delete($userId, $transactionId)
    {
        $transaction = $this->getTransaction($userId, $transactionId);

        if (empty($transaction)) {
            throw new Exception('Transaction does not exist');
        }

        $this->db->exec('
            DELETE
            FROM Transactions
            WHERE id = ' . $transactionId
        );
    }

    public function getAllAccountTransactions($accountId, $month)
    {
        $start_date = date('Y-m-01 00:00:00', strtotime($month));
        $end_date = date('Y-m-t 23:59:59', strtotime($month));

        $transactions = $this->db->exec('
            SELECT id, amount, type, summary, date_time
            FROM Transactions
            WHERE date_time BETWEEN "' . $start_date . '" AND "' . $end_date . '"
                AND (
                    Accounts_from = ' . $accountId . '
                    OR Accounts_to = ' . $accountId . '
                )
        ');

        return $transactions;
    }

    public function getAllTransactions($userId)
    {
        $transactions = [];
        $transactions['transformer'] = $this->transformer;
        $transactions['data'] = $this->db->exec('
            SELECT id, amount, type, summary, date_time
            FROM Transactions
            WHERE Users_id = ' . $userId . '
                AND date_time BETWEEN
                    "' . date('Y-m-01 00:00:00', strtotime('this month')) . '"
                    AND "' . date('Y-m-t 23:59:59', strtotime('this month')) . '"
        ');

        return $this->createTransformer($transactions);
    }

    private function getTransaction($userId, $transactionId)
    {
        $transaction = $this->db->exec('
            SELECT
                id,
                Accounts_from,
                Accounts_to,
                Goals_to,
                amount,
                type,
                date_time,
                summary
            FROM Transactions
            WHERE id = ' . $transactionId . '
                AND Users_id = ' . $userId
        );

        return $transaction;
    }

    private function configureSet($saveData)
    {
        $ret = '';

        foreach ($saveData as $column => $value) {
            $ret .= $column . ' = "' . $value . '", ';
        }

        $ret = rtrim($ret, ', ');

        return $ret;
    }
}