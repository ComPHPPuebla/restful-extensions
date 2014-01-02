<?php
require 'vendor/autoload.php';

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
], 'examples');

$resourceInfo = ['username' => 'montealegreluis', 'password' => 'changeme'];

if ($validator->isValid($resourceInfo)) {
    echo 'Resource is valid', "\n";
} else {
    foreach ($validator->errors() as $resourceProperty => $messages) {
        echo "'$resourceProperty' errors: ", implode(', ', $messages), "\n";
    }
}

$resourceInfo = ['username' => 'montealegreluis'];

if ($validator->isValid($resourceInfo)) {
    echo 'Resource is valid';
} else {
    foreach ($validator->errors() as $resourceProperty => $messages) {
        echo "'$resourceProperty' errors: ", implode(', ', $messages), "\n";
    }
}
