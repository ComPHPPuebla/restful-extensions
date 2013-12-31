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

You can additionally pass a second argument to `ValitronValidator`'s constructor with the path to a
translation file for the error messages. The file should return an array like the following:

```php
return [
    'required' => "is required",
    'equals' => "must be the same as '%s'",
    'different' => "must be different than '%s'",
    'accepted' => "must be accepted",
    'numeric' => "must be numeric",
    'integer' => "must be an integer (0-9)",
    'length' => "length must be between %d and %d",
    'min' => "must be greater than %s",
    'max' => "must be less than %s",
    'in' => "contains invalid value",
    'notIn' => "contains invalid value",
    'ip' => "is not a valid IP address",
    'email' => "is not a valid email address",
    'url' => "not a URL",
    'urlActive' => "must be an active domain",
    'alpha' => "must contain only letters a-z",
    'alphaNum' => "must contain only letters a-z and/or numbers 0-9",
    'slug' => "must contain only letters a-z, numbers 0-9, dashes and underscores",
    'regex' => "contains invalid chatacters",
    'date' => "is not a valid date",
    'dateFormat' => "must be date with format '%s'",
    'dateBefore' => "must be date before '%s'",
    'dateAfter' => "must be date after '%s'",
    'contains' => "must contain %s"
];
```
