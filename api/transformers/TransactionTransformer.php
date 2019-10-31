<?php

use League\Fractal;

class TransactionTransformer extends Fractal\TransformerAbstract
{
    public function transform($transactions)
    {
        return [
            'id' => (int) $transactions['id'],
            'amount' => $transactions['amount'],
            'transactionType' => $transactions['type'],
            'date' => $transactions['date_time'],
            'description' => $transactions['summary'],
            'into_account' => $transactions['Accounts_to'],
            'out_of_account' => $transactions['Accounts_from'],
            'into_goal' => $transactions['Goals_to'],
        ];
    }
}
