# Restful extensions

Extensions to create RESTful APIs.

Run the examples in the "examples~ folder. You will have to first try the Symfony Console Commands
in order to create the test database (SQLite), load data, and try the examples.

```bash
$ ./bin/cli dbal:database:create
$ sqlite test.sq3 < examples/import.sql
```

You can also check the documentation of individual packages.

* [Pagination](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Paginator/README.md)
* [Rest Resources](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Rest/README.md)
* [Hypermedia Formatting](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Hypermedia/README.md)
* [Validation](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Validator/README.md)
* Table Gateway for persisting resources
* Slim controllers and middleware
