<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \PHPUnit_Framework_TestCase as TestCase;

class CollectionFormatterTest extends TestCase
{
    protected $baseUrl;

    protected $firstUser;

    protected $secondUser;

    protected $paginator;

    protected $urlHelper;

    protected $formatter;

    protected function setUp()
    {
        $this->baseUrl = 'http://www.comunidadphppuebla.com';
        $this->firstUser = [
            'links' => ['self' => 'http://www.comunidadphppuebla.com/users/1'],
            'data' => ['username' => 'luis', 'password' => 'changeme']
        ];
        $this->secondUser = [
            'links' => ['self' => 'http://www.comunidadphppuebla.com/users/2'],
            'data' => ['username' => 'misraim', 'password' => 'letmein']
        ];
        $this->paginator = $this->getMockBuilder('\ComPHPPuebla\Paginator\PagerfantaPaginator')
                                ->setConstructorArgs([2])
                                ->setMethods(['getCurrentPageResults', 'setCurrentPage', 'haveToPaginate'])
                                ->getMock();
        $this->urlHelper = $this->getMockBuilder('\Slim\Views\TwigExtension')
                                ->setMethods(['site', 'urlFor'])
                                ->getMock();
        $this->formatter = $this->getMockBuilder('\ComPHPPuebla\Hypermedia\Formatter\HAL\ResourceFormatter')
                                ->setMethods(['format'])
                                ->setConstructorArgs([$this->urlHelper, 'users', 'user_id'])
                                ->getMock();
    }

    public function testCanFormatResourceFromPaginator()
    {
        $this->expectsThatCurrentPageResultsReturnCorrectUsers();
        $this->expectsThatSiteUrlHelperIsCalledOnce();
        $this->expectsThatFirstUserGetsFormatted();
        $this->expectsThatSecondUserGetsFormatted();

        $collectionFormatter = new CollectionFormatter($this->urlHelper, $this->formatter);

        $resources = $collectionFormatter->format($this->paginator, ['page' => 1]);

        $this->assertInternalType('array', $resources);
        $this->assertEquals("{$this->baseUrl}?page=1", $resources['links']['self']);
        $this->assertEquals($this->firstUser, $resources['embedded'][0]['users']);
        $this->assertEquals($this->secondUser, $resources['embedded'][1]['users']);
    }

    protected function expectsThatCurrentPageResultsReturnCorrectUsers()
    {
        $expectedUsers = [
            ['user_id' => 1, 'username' => 'luis', 'password' => 'changeme'],
            ['user_id' => 2, 'username' => 'misraim', 'password' => 'letmein'],
        ];
        $this->paginator->expects($this->once())
                        ->method('getCurrentPageResults')
                        ->will($this->returnValue($expectedUsers));
    }

    protected function expectsThatSiteUrlHelperIsCalledOnce()
    {
        $this->urlHelper->expects($this->once())
                        ->method('site')
                        ->will($this->returnValue($this->baseUrl));
    }

    protected function expectsThatFirstUserGetsFormatted()
    {
        $this->formatter->expects($this->at(0))
                        ->method('format')
                        ->will($this->returnValue($this->firstUser));
    }

    protected function expectsThatSecondUserGetsFormatted()
    {
        $this->formatter->expects($this->at(1))
                        ->method('format')
                        ->will($this->returnValue($this->secondUser));
    }
}
