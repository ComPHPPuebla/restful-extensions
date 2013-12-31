# Working with HAL hypermedia formatting

This classes purpose is to give HAL hypermedia format to resources in RESTful APIs.

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
$formatter = new ResourceFormatter($urlHelper, 'users', 'user_id');

$collectionFormatter = CollectionFormatter($urlHelper, $formatter);

$halCollection = $collectionFormatter->format($paginator, $queryStringParams);

var_dump($halCollection);
/*
 * [links => ..., 'embedded' => ...]
 */
```

The following is an example to format a single resource.

```php
use \Slim\Views\TwigExtension;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\ResourceFormatter;

$urlHelper = new TwigExtension();
$formatter = new ResourceFormatter($urlHelper, 'users', 'user_id');

$collectionFormatter = CollectionFormatter($urlHelper, $formatter);
$resource = ['username' => 'montealegreluis', 'password' => 'letmein'];

$halResource = $formatter->format($resource, $queryStringParams);

var_dump($halResource);
/*
 * [links => ..., 'username' => 'montealegreluis', ...]
 */
```
