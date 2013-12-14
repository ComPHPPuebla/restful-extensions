<?php
namespace ComPHPPuebla\Slim\Controller\EventListener;

use \PHPUnit_Framework_TestCase as TestCase;

class FormatResourceListenerTest extends TestCase
{
    protected $request;

    protected $urlHelper;

    protected $formatter;

    protected $event;

    protected $user;

    protected $resource;

    protected function setUp()
    {
        $this->request = $this->getMockBuilder('\Slim\Http\Request')
                              ->disableOriginalConstructor()
                              ->setMethods(['params'])
                              ->getMock();
        $this->urlHelper = $this->getMockBuilder('\Slim\Views\TwigExtension')
                                ->setMethods(['site', 'urlFor'])
                                ->getMock();
        $this->formatter = $this->getMockBuilder('\ComPHPPuebla\Hypermedia\Formatter\HAL\ResourceFormatter')
                                ->setMethods(['format'])
                                ->setConstructorArgs([$this->urlHelper, 'user', 'user_id'])
                                ->getMock();
        $this->event = $this->getMockBuilder('\Zend\EventManager\Event')->getMock();
        $this->user = [
            'links' => ['self' => 'http://www.comunidadphppuebla.com/users/1'],
            'data' => ['username' => 'luis', 'password' => 'changeme']
        ];
        $this->resource = ['user_id' => 1, 'username' => 'luis', 'password' => 'changeme'];
    }

    public function testCanFormatResource()
    {
        $this->expectsThatRequestDoesNotHaveParameters();
        $this->expectsThatEventReturnsResourceAndRequestParamaters();
        $this->expectsThatUserIsFormattedCorrectly();

        $resourceFormatterListener = new FormatResourceListener($this->formatter);

        $resourceFormatterListener($this->event);
    }

    protected function expectsThatRequestDoesNotHaveParameters()
    {
        $this->request->expects($this->once())
                      ->method('params')
                      ->will($this->returnValue([]));
    }

    protected function expectsThatEventReturnsResourceAndRequestParamaters()
    {
        $this->event->expects($this->at(0))
                    ->method('getParam')
                    ->with('resource')
                    ->will($this->returnValue($this->resource));
        $this->event->expects($this->at(1))
                    ->method('getParam')
                    ->with('request')
                    ->will($this->returnValue($this->request));
    }

    protected function expectsThatUserIsFormattedCorrectly()
    {
        $this->formatter->expects($this->once())
                        ->method('format')
                        ->will($this->returnValue($this->user));
    }
}
