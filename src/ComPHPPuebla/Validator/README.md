# Working with resources validation

This class purpose is to validate resources in RESTful APIs.

The default validation implementation uses `Valitron` you can switch implementation through the 
`Validator` interface. Please read `Valitron`'s documentation for additional validation types and
configurations.

```php
use \ComPHPPuebla\Validator\ValitronValidator;

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

if ($validator->isValid($resourceInfo)) {
    // Perform operations...
} else {
    foreach($validator->errors() as $resourceProperty => $messages) {
        echo "'$resourceProperty' errors: ", implode(', ', $messages), "\n";
    }
}
```
