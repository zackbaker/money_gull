Feature: Users
    In order to use the app
    As a User
    I need to be able to log in and sign up

Scenario: User wants to sign up
    Given we use the method "POST" and uri "/users"
    When they pass through email as "testing@testing.com", password as "testing123", and user name as "testing123"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            email
            name
            """
        And the id will be an integer

Scenario: User wants to sign up with same email address
    Given we use the method "POST" and uri "/users"
    When they pass through email as "testing@testing.com", password as "testing1234", and user name as "testing1234"
    Then we will return a error in the scoped "error"
        And the property message will be "Sorry that email already exists"
        And there will be a status code of "409"

Scenario: User wants to sign up with the same user name
    Given we use the method "POST" and uri "/users"
    When they pass through email as "testingg@testingg.com", password as "testing1234", and user name as "testing123"
    Then we will return a error in the scoped "error"
        And the property message will be "Sorry that user name is taken"
        And there will be a status code of "409"

Scenario: A returning User would like to log in
    Given we use the method "GET" and uri "/users"
    When they type in their email as "test@test.com" and password as "testing123"
    Then we will check it against the database and send back info in the scope of "data"
        And the properties would be:
            """
            id
            email
            name
            """
        And the usersId will be an integer

Scenario: A returning user enters wrong email
    Given we use the method "GET" and uri "/users"
    When they type in their email as "testingg@testingg.com" and password as "testing123"
    Then we will return a error in the scoped "error"
        And the property message will be "Email or Password is incorrect"
        And there will be a status code of "403"

Scenario: A returning user enters wrong password
    Given we use the method "GET" and uri "/users"
    When they type in their email as "testing@testing.com" and password as "testing1234"
    Then we will return a error in the scoped "error"
        And the property message will be "Email or Password is incorrect"
        And there will be a status code of "403"

Scenario: A User would like to delete themselves
    Given we use the method "DELETE" and uri "/users"
    When they type in their email as "testing@testing.com" and password as "testing123" for deletion
    Then we will return "true" for deletion of user.