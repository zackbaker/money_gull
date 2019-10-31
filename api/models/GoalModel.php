<?php

use League\Fractal;

class GoalModel extends ApiModel
{
    private $transformer = 'GoalTransformer';
    protected $pageCount = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function saveGoal($userId, $saveData)
    {
        $this->db->exec('
            INSERT INTO Goals (Users_id, name, amount_needed, amount_saved)
            VALUES (
                ' . $userId . ',
                "' . $saveData['goal_name'] . '", ' .
                $saveData['amount_needed'] . ', ' .
                $saveData['amount_saved'] . '
            )
        ');
    }

    public function getLastGoal($userId)
    {
        $goal['data'] = $this->db->exec('
            SELECT name, amount_needed, amount_saved
            FROM Goals
            WHERE Users_id = ' . $userId . '
            ORDER BY id DESC
            LIMIT 1
        ');

        if (empty($goal)) {
            throw new Exception('No Goals for this user');
        } else {
            $goal['transformer'] = $this->transformer;
            return $this->createTransformer($goal);
        }
    }

    public function deleteGoal($userId, $goalId)
    {
        $goal = $this->checkForExistingGoal($userId, $goalId);

        if (empty($goal)) {
            throw new Exception('Goal does not exist');
        } else {
            $this->db->exec('
                DELETE
                FROM Goals
                WHERE id = ' . $goalId
            );

            return true;
        }
    }

    public function getAllGoals($userId, $pageNum)
    {
        $goals = [];
        $goals['data'] = $this->db->exec('
            SELECT id, name, amount_needed, amount_saved
            FROM Goals
            WHERE Users_id = ' . $userId . '
            LIMIT ' . ($pageNum - 1) * $this->pageCount . ', ' . $this->pageCount
        );

        $pagination = $this->getPagination(
            $userId,
            'goals',
            [
                'pageNum' => $pageNum,
                'totalCount' => $this->getTotalGoals($userId),
            ]
        );

        if (empty($goals['data'])) {
            throw new Exception('No goals found');
        } else {
            $goals['transformer'] = $this->transformer;
            return $this->createTransformer($goals, [], $pagination);
        }
    }

    public function updateGoal($userId, $goalId, $updates = [])
    {
        $goal = $this->checkForExistingGoal($userId, $goalId);

        if (empty($goal)) {
            throw new Exception('Goal does not exist');
        }

        $this->db->exec('
            UPDATE Goals
            SET name = "' . $updates['goal_name'] . '",
                amount_needed = "' . $updates['amount_needed'] . '",
                amount_saved = "' . $updates['amount_saved'] . '"
            WHERE id = ' . $goalId
        );

        $savedGoal = [];
        $savedGoal['transformer'] = $this->transformer;
        $savedGoal['data'] = $this->db->exec('
            SELECT id, name, amount_saved, amount_needed
            FROM Goals
            WHERE id = ' . $goalId
        );

        return $this->createTransformer($savedGoal);
    }

    public function getGoal($userId, $goalId, $transactionMonth)
    {
        $goal = [];
        $goal['transformer'] = $this->transformer;
        $goal['data'] = $this->db->exec('
            SELECT id, name, amount_needed, amount_saved
            FROM Goals
            WHERE Users_id = ' . $userId . '
                AND id = ' . $goalId
        );

        if (empty($goal['data'])) {
            throw new Exception('Goal does not exist');
        }

        $TransactionsModel = new TransactionModel();

        $transactions = $TransactionsModel->getAllAccountTransactions(
            $userId,
            $transactionMonth
        );

        return $this->createTransformer(
            $goal,
            [
                'TransactionTransformer' => $transactions,
            ]
        );
    }

    public function checkForExistingGoal($userId, $goalId)
    {
        return $this->db->exec('
            SELECT id
            FROM Goals
            WHERE Users_id = ' . $userId . '
                AND id = ' . $goalId
        );
    }

    private function getTotalGoals($userId)
    {
        $total = $this->db->exec('
            SELECT COUNT(id) AS count
            FROM Goals
            WHERE Users_id = ' . $userId
        )[0];

        return $total['count'];
    }
}
