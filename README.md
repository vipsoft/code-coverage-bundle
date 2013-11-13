# CodeCoverage

Code coverage (Symfony 2) bundle for remote coverage collection.

Designed for use with the Behat code coverage extension.

## Source

[Github](https://github.com/vipsoft/code-coverage-bundle)

[![Build Status](https://travis-ci.org/vipsoft/code-coverage-bundle.png?branch=master)](https://travis-ci.org/vipsoft/code-coverage-bundle)

## Configuration

The bundle stores code coverage in the cache folder using SQLite.  The default
database file name is "code_coverage.dbf".

```
vipsoft_codecoverage:
    default: sql
    sql:
        database: code_coverage.dbf
```

## See Also

[Code Coverage Extension](https://github.com/vipsoft/code-coverage-extension)

## Copyright

Copyright (c) 2013 Anthon Pang. See LICENSE for details.

## Credits

* Anthon Pang [robocoder](http://github.com/robocoder)
* [Others](https://github.com/vipsoft/code-coverage-bundle/graphs/contributors)
