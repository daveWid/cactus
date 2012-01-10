# Cactus

Cactus is a ORM using the DataMapper pattern for PHP 5.3+

TODO: Fill the rest of this out.

## Running Tests

Since this is an ORM, you will need to test a database. To connect to the database
you will need to to modify the tests/bootstrap.php file with your settings.
Before you commmit changes make sure you run

~~~
git update-index --assume-unchanged tests/bootstrap.php
~~~

This way your username/passwords don't get pushed into the repo.

--

Developed by [Dave Widmer](http://www.davewidmer.net)