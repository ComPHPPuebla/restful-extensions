<?php
namespace ComPHPPuebla\Doctrine\TableGateway\EventListener;

use \PHPUnit_Framework_TestCase as TestCase;

class HasTimestampListenerTest extends TestCase
{
    protected $event;

    protected $user;

    protected function setUp()
    {
        $this->event = $this->getMockBuilder('\Zend\EventManager\Event')
                            ->setMethods(['getParam'])
                            ->getMock();
        $this->user = ['username' => 'luis', 'password' => 'changeme'];
    }

    public function testListenerAddsTimestampAuditValuesOnInsert()
    {
        $cacheListener = new HasTimestampListener();

        $this->expectsThatEventGetParamMethodIsCalled();

        $cacheListener->onInsert($this->event);
        $params = $this->event->getParams();

        $this->assertArrayHasKey('created_at', $params['values']);
        $this->assertArrayHasKey('last_updated_at', $params['values']);
    }

    public function testListenerAddsTimestampAuditValuesOnUpdate()
    {
        $cacheListener = new HasTimestampListener();

        $this->expectsThatEventGetParamMethodIsCalled();

        $cacheListener->onUpdate($this->event);
        $params = $this->event->getParams();

        $this->assertArrayNotHasKey('created_at', $params['values']);
        $this->assertArrayHasKey('last_updated_at', $params['values']);
    }

    protected function expectsThatEventGetParamMethodIsCalled()
    {
        $this->event->expects($this->at(0))
                    ->method('getParam')
                    ->with('values')
                    ->will($this->returnValue($this->user));
    }
}
