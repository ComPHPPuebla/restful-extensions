<?php
namespace ComPHPPuebla\Slim\Controller\EventListener;

use \Zend\EventManager\Event;
use \Zend\EventManager\EventManager;
use \PHPUnit_Framework_TestCase as TestCase;
use \ArrayObject;

class FormatResourceHandlerTest extends TestCase
{
    public function testCanFormatResource()
    {
        $request = $this->getMockBuilder('\Slim\Http\Request')
                        ->disableOriginalConstructor()
                        ->setMethods(['params'])
                        ->getMock();

        $request->expects($this->once())
                ->method('params')
                ->will($this->returnValue([]));

        $urlHelper = $this->getMockBuilder('\Slim\Views\TwigExtension')
                          ->setMethods(['site', 'urlFor'])
                          ->getMock();

        $formatter = $this->getMockBuilder('\ComPHPPuebla\Hypermedia\Formatter\HAL\ResourceFormatter')
                          ->setMethods(['format'])
                          ->setConstructorArgs([$urlHelper, 'user', 'user_id'])
                          ->getMock();

        $user = [
            'links' => ['self' => 'http://www.comunidadphppuebla.com/users/1'],
            'data' => ['username' => 'luis', 'password' => 'changeme']
        ];

        $formatter->expects($this->once())
                  ->method('format')
                  ->will($this->returnValue($user));

        $resourceFormatterHandler = new FormatResourceListener($formatter);

        $eventManager = new EventManager();
        $eventManager->attach('formatResource', $resourceFormatterHandler);

        $resource = new ArrayObject(
            ['user_id' => 1, 'username' => 'luis', 'password' => 'changeme']
        );

        $event = new Event('formatResource');
        $event->setTarget($this)->setParams(['resource' => $resource, 'request' => $request]);
        $eventManager->trigger($event);

        $this->assertEquals($user, $event->getParam('resource'));
    }
}
