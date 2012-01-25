# Cactus

Cactus is a ORM based on the DataMapper library for PHP 5.3+

## Example

We will walk through a quick example on how to use Cactus. First we will start
with a table called `user` that we will builder our example around.

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

Working with this structure we can now dive into drivers and entities.

### Driver

The driver is the class that holds all of the information about the database,
column names, interactions, relationships, etc...

For this example we will be using the built in PDO driver. For the PDO driver you
need to set up your database credentials, which can be done with the following call
anywhere in your code before you run a database query.

``` php
<?php

$pdo = new PDO(string $dsn [, string $username [, string $password [, array $driver_options ]]]);
\Cactus\PDO\Driver::pdo($pdo);
```

_See the [PDO](http://www.php.net/manual/en/class.pdo.php) docs for more
information on creating a PDO connection._

Here is a driver class for our `user` table.

``` php
<?php

class ModelUser extends \Cactus\PDO\Driver
{
	/**
	 * @var   string   The name of the table
	 */
	protected $table = "user";

	/**
	 * @var   string   The name of the primary key column
	 */
	protected $primary_key = "user_id";

	/**
	 * @var   array    The list of columns in the table
	 */
	protected $columns = array('user_id','email','password','last_name','first_name','status','create_date');

	/**
	 * @var   string   The name of the object to return in operations
	 */
	protected $object_class = "User";

	protected $relationships = array(
		// Roles
		'role' => array(
			'type' => \Cactus\Relationship::HAS_MANY,
			'driver' => "ModelUserRole",
			'column' => 'user_id'
		)
	);
}
```

### Entity

An entity is a representation of a database row as a native object. All validation
should be contained within the entity.

Below is a sample entity class for our user.

``` php
<?php

class User extends \Cactus\Entity{}
```

Pretty simple huh?

## Querying

There are 3 types of queries built in, finding, saving and deleting.

### Find

``` php
<?php
$model = new ModelUser;

// Find the user with a user_id of 1
$user = $model->get(1);

// Get all users
$users = $model->all();

// Find user who's email is test@foo.com
$found = $model->find(array(
	'email' => "test@foo.com"
));
```

### Save
``` php
<?php
// New user assuming $post is posted form data
$user = new User($post);
$model->save($user);

// Existing user, after modifications
$user = $model->get(1);
$user->first_name = "Billy";
$model->save($user);
```

### Delete
``` php
<?php
$user = $model->get(1);
$model->delete($user);
```

## Relationships

Building relationships with Cactus is easy. Within your Driver class you will
need to add information to the `protected $relationships` array. In the ModelUser
example above we specified a relationship using the following.

``` php
<?php

protected $relationships = array(
	// Roles
	'role' => array(
		'type' => \Cactus\Relationship::HAS_MANY,
		'driver' => "ModelUserRole",
		'column' => 'user_id',
	//	'loading' => \Cactus\Loading::LAZY,
	)
);
```

The relationships array keys (in our case `role`) are the property names that will
be set on the entity. The configuration array for each key can have the following
options.

 Key | Type | Description | Required
-----|------|-------------|----------
type | `string` | The type of relationship we are forming | Yes
driver | `string` | The name \Cactus\Driver class that is used to get the relationship | Yes
column | `string` | The name of the column to join the tables on | Yes
loading | `int` | The type of loading to use | No (defaults to Lazy loading)

There are only 2 types of relationships in Cactus, `\Cactus\Relationship::HAS_MANY`
and `\Cactus\Relationship::HAS_ONE`.

For loading you can use either `\Cactus\Loading::LAZY` or `\Cactus\Loading::EAGER`.
Cactus loads relationship using the lazy method by default. Eagerly loaded relationships
are loaded in a way to avoid the N+1 select problem.

## Framework Drivers

Instead of needed to rely on the PDO class, Cactus also comes bundled with drivers
for the different php frameworks.

### Kohana

Cactus has a driver and entity for the [Kohana](http://www.kohanaframework.org) framework.

To use the driver, you will need to activate the database module and set your database
configuration as you normally would. Your driver classes will then need to extend
`\Cactus\Kohana\Driver` instead of `\Cactus\PDO\Driver`. The Kohana driver uses the
query builder classes to create queries.

To use the Kohana based entity class your entities will need to extend `\Cactus\Kohana\Entity`.
The Kohana entity class adds in validation and error messages that are based on the
built-in Validation library.

### Creating Your Own Driver

If you don't see drivers for your favorite framework, feel free to fork this repo
and add them in. The only requirement for a driver is that it extends `\Cactus\Driver`
and implements all of the methods in `\Cactus\DriverInterface`.

## API

Please open up `api/index.html` for full documentation on the Cactus library.

## Hacking

Cactus is in heavy development and all contributions are welcome and encouraged.
Before you start hacking away, make sure you switch over to the `develop` branch.

## Running Tests

Before you send a pull request on any changes, make sure that the phpunit tests pass,
adding tests where necessary.

Since this is an ORM, you will need to test a database. To connect to the database
you will need to to modify the tests/bootstrap.php file with your settings.
Before you commmit changes make sure you run...

~~~ shell
git update-index --assume-unchanged tests/bootstrap.php
~~~

This way your username/passwords don't get pushed into the repo.

----

Developed by [Dave Widmer](http://www.davewidmer.net)