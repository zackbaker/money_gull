<?php 

class Users extends ApiModel {
    protected $User;

    public function __construct() {
        parent::__construct();
        $this->User = new UserModel();
    }

    public function dropUsers() {
        $this->db->exec('
            SET FOREIGN_KEY_CHECKS = 0;
        ');

        $this->db->exec('
            TRUNCATE Users
        ');

        $this->db->exec('
            SET FOREIGN_KEY_CHECKS = 1;
        ');
    }

    public function createUsers($faker, $amount = 15) {
        for ($i = 0; $i < $amount; $i++) {
            if ($i == 0) {
                $this->createUserOne();
            } else {
                $userInfo = [
                    'email' => $faker->email,
                    'password' => $faker->password,
                    'name' => $faker->userName,
                ];
                
                $this->User->createUser($userInfo);
            }

            // come back to this if it ends up being important?
            // $this->addSettings($i);
        }
    }

    private function createUserOne() {
        $userInfo = [
            'email' => 'test@test.com',
            'password' => 'testing123',
            'name' => 'testing',
        ];
        
        $this->User->createUser($userInfo);
    }

    private function addSettings($id) {
        $this->db->exec('
            INSERT INTO Settings (Users_id)
            VALUES (' . $id . ')
        ');
    }

    protected function getUserIdList() {
        return $this->db->exec('
            SELECT id
            FROM Users
        ');
    }
}
