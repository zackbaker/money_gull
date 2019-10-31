Feature: Accounts
    In order to keep track of money
    As a User
    I need to be able to keep track of my Accounts

Scenario: Adding a Account
    Given we use the method "POST" and uri "/users/1/accounts"
    When they pass through accountName as "Savings" and accountAmount as "1000.00"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            name
            amount
            """
        And the id will be an integer
        And there will be a status code of "200"

Scenario: Deleteing a Account
    Given we use the method "DELETE" and uri "/users/1/accounts/:lastAccountId"
    Then we check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            message
            """
        And there will be a status code of "200"

Scenario: Deleteing a non existing Account
    Given we use the method "DELETE" and uri "/users/1/accounts/1000000"
    Then we will return a error in the scoped "error"
        And the property message will be "Sorry, this account does not exist"
        And there will be a status code of "400"

Scenario: Listing Accounts
    Given we use the method "GET" and uri "/users/1/accounts"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            name
            amount
            """
        And pagination properties will be:
            """
            total
            count
            per_page
            current_page
            total_pages
            next_url
            previous_url
            """
        And the id will be an integer
        And the response code will be "200"

Scenario: Updating Account
    Given we use the method "PUT" and the uri "/users/1/accounts/1"
    When we pass name as "testingStuff" and amount as "0.01"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            name
            amount
            """
        And the id will be an integer
        And there will be a status code of "200"

Scenario: Updating a non existing Account
    Given we use the method "PUT" and the uri "/users/1/accounts/1100"
    When we pass name as "testingStuff" and amount as "0.01"
    Then we will check it against the database and send back info in the scope of "error"
        And the property message will be "Sorry, this account does not exist"
        And there will be a status code of "403"

Scenario: Looking at a single account that belongs to user
    Given we use the method "GET" and the uri "/users/1/accounts/1"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            name
            amount
            """
        And transactions properties will be:
            """
            id
            amount
            transactionType
            date
            description
            """
        And the accountId will be an integer
        And the transactionId will be an integer
        And the response code will be "200"