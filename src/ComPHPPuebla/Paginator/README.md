# Working with resources

This classes purpose is to paginate resource collections in RESTful APIs.

Here's an example to retrieve all the resources with pagination. The default implementation is using
`Pagerfanta`, but you could use the `Paginator` and `PaginatorFactory` interfaces to switch to your
preferred implementation.

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Paginator\PagerfantaPaginatorFactory;

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);
$factory = new PagerfantaPaginatorFactory(new PagerfantaPaginator($pageSize = 2));
$userTable = new UserTable('users', $connection);

$paginator = $factory->createPaginator(['page' => 1, 'page_size' => 2], $userTable);

if ($paginator->haveToPaginate()) {
    echo 'Showing page ', $paginator->getCurrentPage(), ' of ', $paginator->getNbPages(), "\n";
    
    if ($paginator->hasPreviousPage()) {
        echo 'Previous ', $paginator->getPreviousPage(), "\n";
    }
    
    if ($paginator->hasNextPage()) {
        echo 'Next ', $paginator->getNextPage(), "\n";
    }
    
    foreach ($paginator->getCurrentPageResults() as $user) {
        echo $user['username'];
    }
}
```

If you try to fetch a page that does not exist a `PageOutOfRangeException` is thrown.

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Paginator\PagerfantaPaginatorFactory;
use \ComPHPPuebla\Paginator\PageOutOfRangeException;

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);
$factory = new PagerfantaPaginatorFactory(new PagerfantaPaginator($pageSize = 2));
$userTable = new UserTable('users', $connection);

try {
    $paginator = $factory->createPaginator(['page' => 100, 'page_size' => 2], $userTable);
} catch (PageOutOfRangeException $e) {
    //Handle exception... page 100 does not exist.
}
```
