Feature: GitHub RestFul Api Testing with Behat

  As a behat user
  I want to test restful api of the GitHub
  So that it will bring smile for behat community

  Scenario: GitHub behat Demo Api
    Given I request "/app_dev.php/posts.json"
    Then the response should be JSON
    And the response status code should be 200