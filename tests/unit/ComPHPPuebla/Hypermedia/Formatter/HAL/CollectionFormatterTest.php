<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \PHPUnit_Framework_TestCase as TestCase;

class CollectionFormatterTest extends TestCase
{
    public function testCanFormatResourceFromPaginator()
    {
        $paginator = $this->getMockBuilder('\ComPHPPuebla\Paginator\PagerfantaPaginator')
                          ->setConstructorArgs([2])
                          ->setMethods(['getCurrentPageResults', 'setCurrentPage', 'haveToPaginate'])
                          ->getMock();

        $expectedUsers = [
            ['user_id' => 1, 'username' => 'luis', 'password' => 'changeme'],
            ['user_id' => 2, 'username' => 'misraim', 'password' => 'letmein'],
        ];

        $paginator->expects($this->once())
                  ->method('getCurrentPageResults')
                  ->will($this->returnValue($expectedUsers));

        $urlHelper = $this->getMockBuilder('\Slim\Views\TwigExtension')
                          ->setMethods(['site', 'urlFor'])
                          ->getMock();

        $baseUrl = 'http://www.comunidadphppuebla.com';
        $urlHelper->expects($this->exactly(1))
                  ->method('site')
                  ->will($this->returnValue($baseUrl));

        $formatter = $this->getMockBuilder('\ComPHPPuebla\Hypermedia\Formatter\HAL\ResourceFormatter')
                          ->setMethods(['format'])
                          ->setConstructorArgs([$urlHelper, 'user', 'user_id'])
                          ->getMock();

        $firstUser = [
            'links' => ['self' => 'http://www.comunidadphppuebla.com/users/1'],
            'data' => ['username' => 'luis', 'password' => 'changeme']
        ];

        $formatter->expects($this->at(0))
                  ->method('format')
                  ->will($this->returnValue($firstUser));

        $secondUser = [
            'links' => ['self' => 'http://www.comunidadphppuebla.com/users/2'],
            'data' => ['username' => 'misraim', 'password' => 'letmein']
        ];

        $formatter->expects($this->at(1))
                  ->method('format')
                  ->will($this->returnValue($secondUser));

        $collectionFormatter = new CollectionFormatter($urlHelper, 'users', $formatter);

        $resources = $collectionFormatter->format($paginator, ['page' => 1]);

        $this->assertInternalType('array', $resources);
        $this->assertEquals("$baseUrl?page=1", $resources['links']['self']);
        $this->assertEquals($firstUser, $resources['embedded'][0]['users']);
        $this->assertEquals($secondUser, $resources['embedded'][1]['users']);
    }
}
