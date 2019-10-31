<?php

class Goals extends Users {
    public function __construct() {
        parent::__construct();
    }

    public function deleteGoals() {
        $this->db->exec('
            SET FOREIGN_KEY_CHECKS = 0;
        ');

        $this->db->exec('
            TRUNCATE Goals
        ');

        $this->db->exec('
            SET FOREIGN_KEY_CHECKS = 1;
        ');
    }

    public function createGoals($faker, $amount = 2) {
        $userIds = $this->getUserIdList();

        foreach ($userIds as $k => $id) {
            for ($i = 0; $i < $amount; $i++) {
                $amountSaved = $faker->randomFloat(2, 0, 25);
                $amountNeeded = $faker->randomFloat(2, 0, 4000) + $amountSaved;

                $this->db->exec('
                    INSERT INTO Goals (
                        Users_id,
                        name,
                        amount_needed,
                        amount_saved
                    )
                    VALUES (
                        ' . $id['id'] . ',
                        "' . $faker->word . '",
                        ' . $amountNeeded . ',
                        ' . $amountSaved . '
                    )
                ');
            }
        }
    }
}
