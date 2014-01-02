# RESTful extensions

Extensions to create RESTful APIs.

Run the examples in the `examples` folder. You will have to first use one of the Symfony Console
Commands in order to create the test database (SQLite), and load some data.

```bash
$ ./bin/cli dbal:database:create
$ sqlite3 test.sq3 < examples/import.sql
```

To reset the `examples` database you can drop the database and load the data again.

```bash
$ ./bin/cli dbal:database:drop --force
$ ./bin/cli dbal:database:create
$ sqlite3 test.sq3 < examples/import.sql
```

You can also check the documentation of individual packages.

* [Pagination](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Paginator/README.md)
* [Rest Resources](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Rest/README.md)
* [Hypermedia Information](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Hypermedia/README.md)
* [Hypermedia Rendering](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Twig/README.md)
* [Validation](https://github.com/ComPHPPuebla/restful-extensions/blob/docs_examples/src/ComPHPPuebla/Validator/README.md)
* Table Gateway for persisting resources
* Slim controllers and middleware
