# Working with resources

This classes purpose is to work with resources in RESTful APIs.

Here's an example to retrieve all the resources with pagination. The default implementation is using
`Pagerfanta`, but you could use the `Paginator` and `PaginatorFactory` interfaces to switch to your
preferred implementation.

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Rest\ResourceCollection;
use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Paginator\PagerfantaPaginatorFactory;

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);
$paginator = new PagerfantaPaginator($pageSize = 2);

$usersCollection = new ResourceCollection(
    new UserTable('users', $connection), new PagerfantaPaginatorFactory($paginator)
);

$users = $usersCollection->retrieveAll();

foreach ($users->getCurrentPageResults() as $user) {
    echo $user['username'];
}
```

Here's an example to retrieve a single resource by ID. The default implementation for `Table` is
using Doctrine DBAL but you could switch to another implementation.

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Rest\ResourceCollection;

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);

$usersCollection = new ResourceCollection(new UserTable('users', $connection));

$user = $usersCollection->retrieveOne($id = 1);

echo $user['username'];
```

Persisting a resource is done like in the next example. Default validation implementation uses
`Valitron` you can switch implementation through the `Validator` interface.

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Rest\Resource;
use \ComPHPPuebla\Validator\ValitronValidator;

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);

$validator = new ValitronValidator([
    'required' => [[[
        'username',
        'password',
    ]]],
    'length' => [
        ['username', 1, 15],
        ['password', 1, 20],
    ],
]);

$user = new Resource(new UserTable('users', $connection), $validator);
$userInfo = ['username' => 'montealegreluis', 'password' => 'changeme'];

if ($user->isValid($userInfo)) {
    $user->create($userInfo);
} else {
    foreach($user->errors() as $userProperty => $messages) {
        echo "'$userProperty' errors: ", implode(', ', $messages), "\n";
    }
}
```

Updating a resource is almost identical to creating it.

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Rest\Resource;
use \ComPHPPuebla\Validator\ValitronValidator;

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);

$validator = new ValitronValidator([
    'required' => [[[
        'username',
        'password',
    ]]],
    'length' => [
        ['username', 1, 15],
        ['password', 1, 20],
    ],
]);

$user = new Resource(new UserTable('users', $connection), $validator);
$userInfo = ['username' => 'montealegreluis'];

if ($user->isValid($userInfo)) {
    $user->update($userInfo);
} else {
    foreach($user->errors() as $userProperty => $messages) {
        echo "'$userProperty' errors: ", implode(', ', $messages), "\n";
    }
}
```

Deleting a resource works the following way:

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Rest\ResourceCollection;

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);

$usersCollection = new ResourceCollection(new UserTable('users', $connection));

$usersCollection->delete($id = 1);
```

If you want to provide a single resource or a collection of resources OPTIONS you can do it the
following way:

```php
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Rest\ResourceOptions;

$usersOptions = new ResourceOptions(
    $options = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'],
    $collectionOptions = ['GET', 'POST', 'OPTIONS']
);

echo implode(', ', $usersOptions->getOptions()), "\n";
echo implode(', ', $usersOptions->getCollectionOptions()), "\n";
```
