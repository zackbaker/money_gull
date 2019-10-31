<?php

class GoalController extends ApiController
{
    private $Goal;

    public function __construct()
    {
        parent::__construct();
        $this->Goal = new GoalModel();
    }

    public function saveGoal($f3)
    {
        $userId = $f3->get('PARAMS.userId');
        $goalInfo = $this->getPost();
        $goalInfo = $this->Goal->sanatizeData($goalInfo);
        $this->Goal->saveGoal($userId, $goalInfo);

        try {
            echo $this->Goal->getLastGoal($userId);
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }

    public function deleteGoal($f3)
    {
        $userId = $f3->get('PARAMS.userId');
        $goalId = $f3->get('PARAMS.goalId');

        try {
            $this->Goal->deleteGoal($userId, $goalId);
            echo $this->sendSucessMessage(200, 'Completed');
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                403,
                $e->getMessage()
            );
        }
    }

    public function getAllGoals($f3)
    {
        if (!empty($_GET)) {
            $pageNum = $this->Goal->sanatizeData($_GET);
            $pageNum = $pageNum['page_num'];
        } else {
            $pageNum = 1;
        }

        try {
            echo $this->Goal->getAllGoals(
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

    public function updateGoal($f3)
    {
        $updateInfo = $this->Goal->sanatizeData($_GET);

        try {
            echo $this->Goal->updateGoal(
                $f3->get('PARAMS.userId'),
                $f3->get('PARAMS.goalId'),
                $updateInfo
            );
        } catch (Exception $e) {
            echo $this->sendErrorResponse(
                403,
                $e->getMessage()
            );
        }
    }

    public function getSingleGoals($f3)
    {
        $transactions = $this->Goal->sanatizeData($_GET);

        if (empty($transactions['month'])) {
            $transactions['month'] = date('Y-m-01');
        }

        try {
            echo $this->Goal->getGoal(
                $f3->get('PARAMS.userId'),
                $f3->get('PARAMS.goalId'),
                $transactions['month']
            );
        } catch (Exeption $e) {
            echo $this->sendErrorResponse(
                400,
                $e->getMessage()
            );
        }
    }
}
