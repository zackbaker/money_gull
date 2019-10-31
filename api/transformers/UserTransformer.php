<?php

use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract {
    public function transform($user) {
        return [
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'name' => $user['user_name'],
        ];
    }
}
