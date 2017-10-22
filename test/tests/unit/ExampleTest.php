<?php


class ExampleTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        // create a user from framework, user will be deleted after the test
        $id = $this->tester->haveInRepository('Acme\Model\ProductModel', ['productName' => 'miles', 'createdAt' => new \Datetime(), 'updatedAt' => new \Datetime()]);
    }
}