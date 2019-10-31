<?php

class UserController extends ApiController {
    private $User;

    public function __construct(){
        parent::__construct();
        $this->User = new UserModel();
    }

    public function signUp() {
        $data = $this->getPOST();
        $data = $this->User->sanatizeData($data);
        $existingUser = $this->User->findExistingUser($data);

        if (empty($existingUser)) {
            $this->User->createUser($data);
            echo $this->User->getReturnUserInfo($data);
        } else {
            $error = $this->findSignUpError($data, $existingUser['data']);
            echo $this->sendErrorResponse(409, $error);
        }
    }

    public function signIn() {
        $loginInfo = $this->User->sanatizeData($_GET);
        $user = $this->User->checkLogin($loginInfo);

        if (!empty($user)) {
            echo $this->User->getReturnUserInfo(null, $user);
        } else {
            echo $this->sendErrorResponse(
                403,
                'Email or Password is incorrect'
            );
        }
    }

    public function deleteUser($f3) {
        $deleteInfo = $this->User->sanatizeData($_GET);
        $user = $this->User->checkLogin($deleteInfo);

        if (!empty($user)) {
            $this->User->deleteUser($f3->get('PARAMS.userId'));
            echo 'true';
        } else {
            echo 'false';
        }
    }

    private function findSignUpError($data, $existingUser) {
        $matches = array_intersect_assoc($data, $existingUser);
        $matches = array_keys($matches);

        switch ($matches[0]) {
            case 'email':
                $error = 'Sorry that email already exists';
                break;

            case 'name':
                $error = 'Sorry that user name is taken';
                break;

            default:
                $error = 'Sorry something went wrong on our side';
                break;
        }

        return $error;
    }
}