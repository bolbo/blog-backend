<?php

namespace Bolbo\BlogBundle\Features\Context;

use Behat\Behat\Context\Context;
use Exception;
use Symfony\Component\HttpFoundation\Session\Session;
use GuzzleHttp\Client;

class FeatureContext implements Context
{
    public $_response;
    public $_client;
    private $_parameters = array();

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct()
    {

        $baseUrl = 'http://www.bolbo-blog-api.local';
        $client = new Client(['base_url' => $baseUrl]);
        $client->setDefaultOption(
            'headers',
            array('Accept' => 'application/json', 'Content-Type' => 'application/json')
        );
        $this->_client = $client;
    }

    public function getParameter($name)
    {
        if (count($this->_parameters) === 0) {
            throw new \Exception('Parameters not loaded!');
        } else {
            $parameters = $this->_parameters;
            return (isset($parameters[$name])) ? $parameters[$name] : null;
        }
    }

    /**
     * @When /^I request "([^"]*)"$/
     */
    public function iRequest($uri)
    {
        $request = $this->_client->get($uri);
        $this->_response = $request;
    }

    /**
     * @Then /^the response should be JSON$/
     */
    public function theResponseShouldBeJson()
    {
        $data = json_decode($this->_response->getBody(true));
        dump($data);
        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->_response);
        }
    }

    /**
     * @Then /^the response status code should be (\d+)$/
     */
    public function theResponseStatusCodeShouldBe($httpStatus)
    {
        if ((string)$this->_response->getStatusCode() !== $httpStatus) {
            throw new \Exception('HTTP code does not match ' . $httpStatus .
                ' (actual: ' . $this->_response->getStatusCode() . ')');
        }
    }

    /**
     * @Given /^the response has a "([^"]*)" property$/
     */
    public function theResponseHasAProperty($propertyName)
    {
        $data = json_decode($this->_response->getBody(true));
        if (!empty($data)) {
            if (!isset($data->$propertyName)) {
                throw new Exception("Property '" . $propertyName . "' is not set!\n");
            }
        } else {
            throw new Exception("Response was not JSON\n" . $this->_response->getBody(true));
        }
    }

    /**
     * @Then /^the "([^"]*)" property equals "([^"]*)"$/
     */
    public function thePropertyEquals($propertyName, $propertyValue)
    {
        $data = json_decode($this->_response->getBody(true));

        if (!empty($data)) {
            if (!isset($data->$propertyName)) {
                throw new Exception("Property '" . $propertyName . "' is not set!\n");
            }
            if ($data->$propertyName !== $propertyValue) {
                throw new \Exception('Property value mismatch! (given: ' . $propertyValue . ', match: ' . $data->$propertyName . ')');
            }
        } else {
            throw new Exception("Response was not JSON\n" . $this->_response->getBody(true));
        }
    }
}