# Cactus

Cactus is a ORM based on the DataMapper library for PHP 5.3+

## Example

We will walk through a quick example on how to use Cactus. First we will start
with a table called `user` that we will builder our example around.

```sql
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

```php
\Cactus\PDO\Driver::pdo(new PDO(string $dsn [, string $username [, string $password [, array $driver_options ]]]));
```

_See the [PDO](http://www.php.net/manual/en/class.pdo.php) docs for more
information on creating a PDO connection._

Here is a driver class for our `user` table.

```php
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
$user = $model->get(1);
$model->delete($user);
```

## API

There is a lot more you can dig into in this library. Please open up `api/index.html`
for full documentation.

## Hacking

Cactus is in heavy development and all contributions are welcome and encouraged.
Before you start hacking away, make sure you switch over to the `develop` branch.

## Running Tests

Before you send a pull request on any changes, make sure that the phpunit tests pass,
adding tests where necessary.

Since this is an ORM, you will need to test a database. To connect to the database
you will need to to modify the tests/bootstrap.php file with your settings.
Before you commmit changes make sure you run...

~~~ bash
git update-index --assume-unchanged tests/bootstrap.php
~~~

This way your username/passwords don't get pushed into the repo.

--

Developed by [Dave Widmer](http://www.davewidmer.net)