<?php

class Transactions extends Users {
    public function __construct() {
        parent::__construct();
    }

    public function deleteTransations() {
        $this->db->exec('
            SET FOREIGN_KEY_CHECKS = 0;
        ');

        $this->db->exec('
            TRUNCATE Transactions
        ');

        $this->db->exec('
            SET FOREIGN_KEY_CHECKS = 1;
        ');
    }

    public function createTransations($faker, $amount = 30) {
        $users = $this->getUserIdList();

        foreach ($users as $k => $id) {
            $amounts = 0;
            $accountsIds = $this->getAccountIds($id['id']);
            $goalsIds = $this->getGoalsIds($id['id']);

            for ($i = 0; $i < $amount; $i++) {
                $amount = $faker->randomFloat(2, 0, 100);
                $amounts += $amount;
                $date = date('Y-m-d H:i:s', strtotime('-' . $i . 'days'));
                $type = $this->findType();
                $accountTo = 'null';
                $accountFrom = 'null';
                $goalTo = 'null';

                if ($type == 'transfer') {
                    if ($this->findTo() == 'account') {
                        $accountTo = $accountsIds[rand(0, count($accountsIds) - 1)]['id'];
                        $goalTo = 'null';
                    } else {
                        $goalTo = $goalsIds[rand(0, count($goalsIds) - 1)]['id'];
                        $accountTo = 'null';
                    }
                } else if ($type = 'income') {
                    $accountTo = $accountsIds[rand(0, count($accountsIds) - 1)]['id'];
                } else {
                    $accountFrom = $accountsIds[rand(0, count($accountsIds) - 1)]['id'];
                }

                $this->db->exec('
                    INSERT INTO Transactions (
                        Users_id,
                        Accounts_from,
                        Accounts_to,
                        Goals_to,
                        amount,
                        type,
                        date_time,
                        summary
                    )
                    VALUES (
                        ' . $id['id'] . ',
                        ' . $accountFrom . ',
                        ' . $accountTo . ', 
                        ' . $goalTo . ', 
                        ' . $amount . ',
                        "' . $type . '",
                        "' . $date . '",
                        "' . $faker->sentence(10) . '"
                    )
                ');
            }
        }
    }

    private function findType() {
        $randNum = rand(1, 3);

        switch ($randNum) {
            case 1:
                return 'expense';
                break;

            case 2:
                return 'income';
                break;

            case 3:
                return 'transfer';
                break;

            default:
                break;
        }
    }

    private function findTo() {
        return ((rand(0, 1)) ? 'account' : 'goal');
    }

    private function getAccountIds($userId) {
        return $this->db->exec('
            SELECT id
            FROM Accounts
            WHERE Users_id = ' . $userId . '
        ');
    }

    private function getGoalsIds($userId) {
        return $this->db->exec('
            SELECT id
            FROM Goals
            WHERE Users_id = ' . $userId . '
        ');
    }
}
