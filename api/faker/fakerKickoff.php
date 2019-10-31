<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../models/ApiModel.php');

$f3 = \Base::instance();
$f3->set(
    'AUTOLOAD',
    __DIR__ . '/../models/; '
);

$faker = Faker\Factory::create();

spl_autoload_register(function ($class_name) {
    if($class_name != 'DB\SQL'){
        include 'controllers/' . $class_name . '.php';
    }
});

$users = new Users;
$accounts = new Accounts;
$goals = new Goals;
$transactions = new Transactions;

$users->dropUsers();
$users->createUsers($faker);
$accounts->deleteAccounts();
$accounts->createAccounts($faker);
$goals->deleteGoals();
$goals->createGoals($faker);
$transactions->deleteTransations();
$transactions->createTransations($faker);