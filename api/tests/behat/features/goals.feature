Feature: Goals
    In order to stay Goal oriented a user will insert goals
    As a User
    I need to be able to track my Goals

Scenario: Adding a Goal
    Given we use the method "POST" and uri "/users/1/goals"
    When they pass through name as "Cali Vacation", needed as "1000.00", saved as "100.00"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            name
            needed
            saved
            """
        And the id will be an integer
        And there will be a status code of "200"

Scenario: Deleteing a Goal
    Given we use the method "DELETE" and uri "/users/1/goals/:lastGoalsId"
    Then we check it against the database and send back info in the scope of "data"
        And the property action will say "Completed"
        And there will be a status code of "200"

Scenario: Deleteing a non existing goal
    Given we use the method "DELETE" and uri "/users/1/goals/1000000"
    Then we will return a error in the scoped "error"
        And the property message will be "Goal does not exist"
        And there will be a status code of "403"

Scenario: Listing Goals
    Given we use the method "GET" and uri "/users/1/goals"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            name
            needed
            saved
            """
        And pagination properties will be:
            """
            total
            count
            per_page
            current_page
            total_pages
            next_url
            """
        And the id will be an integer
        And the response code will be "200"

Scenario: Updating Goal
    Given we use the method "PUT" and uri "/users/1/goals/1"
    When we pass name as "testingGoals", needed as "1000.01", and saved as 10.00
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            name
            needed
            saved
            """
        And the id will be an integer
        And there will be a status code of "200"

Scenario: Updating a non existing Goal
    Given we use the method "PUT" and uri "/users/1/goals/1100"
    When we pass name as "testingGoals", needed as "1000.01", and saved as 10.00
    Then we will check it against the database and send back info in the scope of "error"
        And the property message will be "Goal does not exist"
        And there will be a status code of "403"

Scenario: Looking at a single account that belongs to user
    Given we use the method "GET" and uri "/users/1/goals/1"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            name
            needed
            saved
            """
        And transactions properties will be:
            """
            id
            amount
            transactionType
            date
            description
            """
        And the goalId will be an integer
        And the transactionId will be an integer
        And the response code will be "200"