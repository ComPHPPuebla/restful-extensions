# Rendering resources with HAL hypermedia formatting

This classes purpose is to render resources with HAL hypermedia format in RESTful APIs.

You can render collections of resources and single resources in XML and JSON format with this Twig
extension.

The following example renders a single resource in JSON and XML formats.

```php
use \Slim\Views\Twig;
use \ComPHPPuebla\Twig\HalRendererExtension;

$resource = [
    'links' => [
        'self' => '/users/1',
    ],
    'username' => 'montealegreluis',
    'password' => 'changeme',
]

$view = new Twig();
$views->parserExtensions = [
    new HalRendererExtension(),
];
$view->setData(['resource' => $resource]);

echo $view->display("resource/show.json.twig"), "\n";
/*
{
    "_links":{
        "self":{
            "href":"\/users\/1"
        }
    },
    "username":"montealegreluis",
    "password":"changeme"
},
*/
echo $view->display("resource/show.xml.twig"), "\n";
/*
<?xml version="1.0"?>
<resource href="/users/1">
    <username>montealegreluis</username>
    <password>changeme</password>
</resource>
*/
```

The following example renders a collection of resources in JSON and XML formats.

```php
use \Slim\Views\Twig;
use \ComPHPPuebla\Twig\HalRendererExtension;

$resources = [
    'links' => [
        'self' => '/users?page=1',
        'next' => '/users?page=2',
    ],
    'embedded' => [
        [
            'links' => [
                'self' => '/users/1',
            ],
            'username' => 'montealegreluis',
            'password' => 'changeme',
        ],
        [
            'links' => [
                'self' => '/users/2',
            ],
            'username' => 'michmendar',
            'password' => 'letmein',
        ],
    ],
];

$view = new Twig();
$views->parserExtensions = [
    new HalRendererExtension(),
];
$view->setData(['resource' => $resources]);

echo $view->display("resource/show.json.twig"), "\n";
/*
{
    "_links":{
        "self":{
            "href":"\/users?page=1"
        },
        "next":{
            "href":"\/users?page=2"
        }
    },
    "_embedded":{
        {
            "_links":{
                "self":{
                    "href":"\/users\/1"
                }
            },
            "username":"montealegreluis",
            "password":"changeme"
        },
        {
            "_links":{
                "self":{
                    "href":"\/users\/2"
                }
            },
            "id":"1",
            "username":"michmendar",
            "password":"letmein"
        }
    }
}
*/
echo $view->display("resource/show.xml.twig"), "\n";
/*
<?xml version="1.0"?>
<link href="/dogs?page=1" rel="self" />
<link href="/dogs?page=2" rel="next" />
<resource href="/users/1">
    <username>montealegreluis</username>
    <password>changeme</password>
</resource>
<resource href="/users/2">
    <username>michmendar</username>
    <password>letmein</password>
</resource>
*/
```

`resource/show.json.twig`

```django
{{ renderJson(resource)|raw }}
```

`resource/show.xml.twig`

```django
{{ renderXml(resource)|raw }}
```
