# Working with HAL hypermedia formatting

This classes purpose is to add HAL hypermedia information to resources in RESTful APIs.

HAL, as defined by its specification is "... A simple format that gives a consistent and easy way
to hyperlink between resources in your API". The default implementation for hypermedia formatting is
HAL but you could switch implementation through the `Formatter` interface.

The following is an example to format a collection of resources.

```php
use \Slim\Views\TwigExtension;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\ResourceFormatter;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\CollectionFormatter;
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Paginator\PagerfantaPaginatorFactory;

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);
$factory = new PagerfantaPaginatorFactory(new PagerfantaPaginator($pageSize = 2));
$userTable = new UserTable('users', $connection);
$queryStringParams = ['page' => 1, 'page_size' => 2];

$paginator = $factory->createPaginator($queryStringParams, $userTable);

$urlHelper = new TwigExtension();
$formatter = new ResourceFormatter($urlHelper, 'user', 'user_id');

$collectionFormatter = new CollectionFormatter($urlHelper, 'users', $formatter);

$halCollection = $collectionFormatter->format($paginator, $queryStringParams);

var_dump($halCollection);
/*
array(3) {
  'embedded' =>
  array(2) {
    [0] =>
    array(1) {
      'users' =>
      array(2) {
      'links' =>
      array(1) {
        'self' =>
        string(24) "http://localhost/users/1"
      }
      'data' =>
      array(3) {
        'user_id' =>
        string(1) "1"
        'username' =>
        string(15) "montealegreluis"
        'password' =>
        string(8) "changeme"
      }
    }
    [1] =>
    array(1) {
      'users' =>
      array(2) {
      'links' =>
      array(1) {
        'self' =>
        string(24) "http://localhost/users/2"
      }
      'data' =>
      array(3) {
        'user_id' =>
        string(1) "2"
        'username' =>
        string(10) "michmendar"
        'password' =>
        string(7) "letmein"
      }
    }
  }
  'data' =>
  array(0) {
  }
  'links' =>
  array(3) {
    'next' =>
    string(41) "/users?page=2&page_size=2"
    'last' =>
    string(41) "/users?page=2&page_size=2"
    'self' =>
    string(41) "/users?page=1&page_size=2"
  }
}
 */
```

The following is an example to format a single resource.

```php
use \Slim\Views\TwigExtension;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\ResourceFormatter;

$urlHelper = new TwigExtension();
$formatter = new ResourceFormatter($urlHelper, 'users', 'user_id');

$formatter = new ResourceFormatter($urlHelper, 'user', 'user_id');
$resource = ['username' => 'montealegreluis', 'password' => 'letmein'];

$halResource = $formatter->format($resource, []);

var_dump($halResource);
/*
array(2) {
  'links' =>
  array(1) {
    'self' =>
    string(24) "http://localhost/users/3"
  }
  'data' =>
  array(3) {
    'user_id' =>
    int(3)
    'username' =>
    string(15) "montealegreluis"
    'password' =>
    string(7) "letmein"
  }
}
 */
```
