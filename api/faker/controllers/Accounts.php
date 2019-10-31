<?php

class Accounts extends Users {
    public function __construct() {
        parent::__construct();
    }

    public function deleteAccounts() {
        $this->db->exec('
            SET FOREIGN_KEY_CHECKS = 0;
        ');

        $this->db->exec('
            TRUNCATE Accounts
        ');

        $this->db->exec('
            SET FOREIGN_KEY_CHECKS = 1;
        ');
    }

    public function createAccounts($faker, $amount = 5) {
        $userIds = $this->getUserIdList();

        foreach ($userIds as $k => $id) {
            for($i = 0; $i < $amount; $i++){
                $this->db->exec('
                    INSERT INTO Accounts (Users_id, name, amount)
                    VALUES (
                        ' . $id['id'] . ',
                        "' . $faker->creditCardType . '",
                        ' . $faker->randomFloat(2, 0, 5000) . '
                    )
                ');
            }
        }
    }
}
