# Cactus

Cactus is a ORM using the DataMapper pattern for PHP 5.3+

## Example

We will walk through a quick example on how to use Cactus. First we will start
with a table called `user` that we will build our example around.

``` sql
CREATE TABLE IF NOT EXISTS `user` (
	`user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`email` varchar(128) NOT NULL,
	`password` varchar(64) DEFAULT NULL,
	`first_name` varchar(50) NOT NULL,
	`last_name` varchar(50) NOT NULL,
	`status` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
```

Working with this structure we can now dive into the model and entity classes.

## Mapper

The mapper classes hold all of the information about the database table it is
mapping, including the table name and primary key.

Here is the model class for our `user` table.

``` php
<?php

class UserMapper extends \Cactus\Mapper
{
	/**
	 * Use the init() function so you don't have to mess with handling the
	 * adapter injection in the constructor.
	 */
	protected function init()
	{
		$this->table = "user";
		$this->primary_key = "user_id";

		$this->columns = array(
			'user_id' => 'integer',
			'email' => 'string',
			'password' => 'string',
			'first_name' => 'string',
			'last_name' => 'string',
			'status' => 'integer'
			'create_date' => 'dateTime'
		);
	}
}
```

The mapper class has no knowledge of how to connect to your data source so you will
have to specify that yourself.

``` php
<?php


$pdo = new PDO($dsn, $username, $password);
$adapter = new \Cactus\Adapter\PDO($pdo);

$mapper = new UserMapper($adapter);
```

As you can see the adapter needs to be injected into the class, so it is probably
wise to use a dependency injection container to accomplish this.

_See the [PDO](http://www.php.net/manual/en/class.pdo.php) docs for more
information on creating a PDO connection._

Using a framework or don't have PDO available? You are in luck because you can
easily create adapters to suit your needs. All you need to do is implement the
`\Cactus\Adapter` interface and inject that adapter into Cactus.

## Entity

An entity is a representation of each data row as an object. All validation
and data normalization should be contained within the entity.

Below is a sample entity class for our user.

``` php
<?php

class User extends \Cactus\Entity{}
```

Pretty simple huh?

## Querying

There are 3 types of queries built in; finding, saving and deleting.

### Find

``` php
<?php

// Find the user with a user_id of 1
$user = $mapper->get(1);

// Get all users
$users = $mapper->all();

// Find user who's email is test@foo.com
$found = $mapper->find(array(
	'email' => "test@foo.com"
));
```

### Save
``` php
<?php

// New user assuming $post is posted form data
$user = new User($post);
$mapper->save($user);

// Existing user, after modifications
$user = $mapper->get(1);
$user->first_name = "Billy";
$mapper->save($user);

echo $user->first_name; // Output: Billy
```

### Delete
``` php
<?php

$user = $mapper->get(1);
$mapper->delete($user);
```

## Defining Columns

The goal of defining the columns is not to be able to generate the sql to
create your tables, but to convert the string values from the database over to
native php types.

Lets take a look at the example from `UserMapper` above

``` php
<?php

$this->columns = array(
	'user_id' => 'integer',
	'email' => 'string',
	'password' => 'string',
	'first_name' => 'string',
	'last_name' => 'string',
	'status' => 'integer'
	'create_date' => 'dateTime'
);
```

As you can see the columns array is setup in a $name => $type setup. For a full
list of field types you can choose from check the documentation for
`\Cactus\Converter`. Setting a field to `false` keeps it as a string as well.


## Building Queries

You can build queries for custom methods in your model anyway you want. Cactus
comes bundled with [Peyote](https://github.com/daveWid/Peyote) if you would like to
build your queries in an object oriented way.

## Supported Adapters

Below is a list of currently supported adapters. If you don't see your framework
in the list, hack the code and send a pull request.

* PDO

## Hacking

Cactus is in heavy development and all contributions are welcome and encouraged.
Before you start hacking away, make sure you switch over to the `develop` branch.

## Running Tests

Before you send a pull request on any changes, make sure that the phpunit tests pass,
adding tests where necessary.

Since this is an ORM, you will need to test a database. To connect to the database
you will need to to modify the `php` section of the phpunit.xml file with your settings.
Before you commit changes make sure you run...

~~~ shell
git update-index --assume-unchanged phpunit.xml
~~~

This way your username/passwords don't get pushed into the repo.

----

Developed by [Dave Widmer](http://www.davewidmer.net)
