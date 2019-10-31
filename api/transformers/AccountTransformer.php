<?php

use League\Fractal;

class AccountTransformer extends Fractal\TransformerAbstract
{
    protected $availableIncludes = [
        'transactions',
    ];

    public function transform($accounts)
    {
        return [
            'id' => (int) $accounts['id'],
            'name' => $accounts['name'],
            'amount' => $accounts['amount'],
        ];
    }
}
