<?php

use League\Fractal;

class GoalTransformer extends Fractal\TransformerAbstract
{
    protected $availableIncludes = [
        'transactions',
    ];

    public function transform($goals)
    {
        return [
            'id' => (int) $goals['id'],
            'name' => $goals['name'],
            'needed' => $goals['amount_needed'],
            'saved' => $goals['amount_saved'],
        ];
    }
}
