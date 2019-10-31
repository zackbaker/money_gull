Feature: Transactions
    In order to keep track of my spending
    As a User
    I need to be able to track my transactions

Scenario: Adding a transaction
    Given we use the method "POST" and uri "/users/1/accounts/1/transactions"
    When they pass through type as "expense", date as "NOW", description as "just wanted something"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            amount
            transactionType
            date
            description
            into_account
            out_of_account
            into_goal
            """
        And the id will be an integer
        And there will be a status code of "200"

Scenario: Adding towards a Goal
    Given we use the method "POST" and uri "/users/1/goals/1/transactions"
    When they pass through type as "income", amount as "11.50", date as "NOW", and description as "Gonna go on this trip"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            amount
            transactionType
            date
            description
            into_account
            out_of_account
            into_goal
            """
        And the id will be an integer
        And there will be a status code of "200"

Scenario: Transfering money from account to goal
    Given we use the method "POST" and uri "/users/1/goals/1/transactions"
    When they pass through account_id as "1", type as "transfer", amount as "10.00", date as "NOW", description as "still gonna make this trip"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            amount
            transactionType
            date
            description
            into_account
            out_of_account
            into_goal
            """
        And the transactionId will be an integer
        And the AccountFromId will be an integer
        And the GoalId will be an integer
        And there will be a status code of "200"

Scenario: Deleteing a Transaction
    Given we use the method "DELETE" and uri "/users/1/transactions/:lastTransactionsId"
    Then we will check it against the database and send back info in the scope of "data"
        And the property action will say "Deletion Successful"
        And there will be a status code of "200"

Scenario: Deleting a non existing transaction
    Given we use the method "DELETE" and uri "/users/1/transactions/1000000"
    Then we will return a error in the scoped "error"
        And the property message will be "Transaction does not exist"
        And there will be a status code of "403"

Scenario: Updating a Transaction
    Given we use the method "PUT" and uri "/users/1/transactions/1"
    When they pass amount as "50.00" and description as "Shoes!"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            amount
            transactionType
            date
            description
            into_account
            out_of_account
            into_goal
            """
        And the id will be an integer
        And there will be a status code of "200"