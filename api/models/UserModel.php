<?php

use League\Fractal;

class UserModel extends ApiModel {
    private $secretSalt = 'KiMedL1345';

    public function __construct(){
        parent::__construct();
    }

    public function findExistingUser($userData) {
        $user = $this->db->exec('
            SELECT email, user_name
            FROM Users
            WHERE
                email = "' . $userData['email'] . '" OR
                user_name = "' . $userData['name'] . '"
        ');

        if(!empty($user)) {
            $transformer = new Fractal\Resource\Item($user[0], new UserTransformer);
            return $this->Fractal->createData($transformer)->toArray();
        } else {
            return false;
        }
    }

    public function createUser($userData) {
        $salt = uniqid(mt_rand(), true);
        $password = $this->getHashedPassword($userData['password'], $salt);

        $this->db->exec('
            INSERT INTO Users (email, password, user_name, salt)
            VALUES(
                "' . $userData['email'] . '",
                "' . $password . '",
                "' . $userData['name'] . '",
                "' . $salt . '"
            )
        ');
    }

    private function getHashedPassword($password, $salt) {
        return hash(
            'sha512',
            hash(
                'sha256',
                $salt . $this->secretSalt
            ) . $password
        );
    }

    public function getReturnUserInfo($userData, $user = null) {
        if (empty($user)) {
            $user = $this->db->exec('
                SELECT id, email, user_name
                FROM Users
                WHERE
                    email = "' . $userData['email'] . '" AND
                    user_name = "' . $userData['name'] . '"
            ');
        }

        $transformer = new Fractal\Resource\Item($user[0], new UserTransformer);
        return $this->Fractal->createData($transformer)->toJson();
    }

    public function checkLogin($loginInfo) {
        $user = $this->db->exec('
            SELECT *
            FROM Users
            WHERE email = "' . $loginInfo['email'] . '"
        ');

        if (!empty($user)) {
            $loginPassHashed = $this->getHashedPassword(
                $loginInfo['password'],
                $user[0]['salt']
            );

            if ($user[0]['password'] != $loginPassHashed) {
                unset($user);
            }
        }

        return $user;
    }

    public function deleteUser($userId) {
        $this->db->exec('
            DELETE
            FROM Users
            WHERE id = ' . $userId
        );

        return true;
    }
}