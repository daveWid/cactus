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

## Model

The model is the class that holds all of the information about the database,
column names, interactions, relationships, etc...

The model class has no knowledge of how to connect to your data source so you will
have to specify that yourself.

``` php
<?php

$pdo = new PDO($dsn, $username, $password);
$adapter = new \Cactus\Adapter\PDO($pdo);

\Cactus\Model::set_adapter($adapter);
```

_See the [PDO](http://www.php.net/manual/en/class.pdo.php) docs for more
information on creating a PDO connection._

Using a framework or don't have PDO available? You are in luck because you can
easily create adapters to suit your needs. All you need to do is implement the
`\Cactus\Adapter` interface and inject that adapter into Cactus. See the list of
supported frameworks below.

Here is the model class for our `user` table.

``` php
<?php

class ModelUser extends \Cactus\Model
{
	/**
	 * Model setup. 
	 */
	public function __construct()
	{
		parent::__construct(array(
			'table' => "user",
			'primary_key' => "user_id",
			'columns' => array(
				'user_id' => \Cactus\Field::VARCHAR,
				'email' => \Cactus\Field::VARCHAR,
				'password' => \Cactus\Field::VARCHAR,
				'last_name' => \Cactus\Field::VARCHAR,
				'first_name' => \Cactus\Field::VARCHAR,
				'status' => \Cactus\Field::INT,
				'create_date' => \Cactus\Field::DATETIME,
			),
			'object_class' => "User",
			'relationships' => array(
				// Roles
				'role' => array(
					'type' => \Cactus\Relationship::HAS_MANY,
					//'loading' => \Cactus\Loading::EAGER,
					'driver' => "ModelUserRole",
					'column' => 'user_id'
				)
			),
		));
	}
}
```

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

// Assuming that the adapter has been setup correctly already...
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

echo $user->first_name; // Output: Billy
```

### Delete
``` php
<?php
$user = $model->get(1);
$model->delete($user);
```

## Defining Columns

The goal of defining the columns is not to be able to generate the sql to
create your tables, but to convert the string values from the database over to
native php types.

Lets take a look at the example from `ModelUser` above

``` php
<?php
'columns' => array(
	'user_id' => \Cactus\Field::VARCHAR,
	'email' => \Cactus\Field::VARCHAR,
	'password' => \Cactus\Field::VARCHAR,
	'last_name' => \Cactus\Field::VARCHAR,
	'first_name' => \Cactus\Field::VARCHAR,
	'status' => \Cactus\Field::INT,
	'create_date' => \Cactus\Field::DATETIME,
),
```

As you can see the columns array is setup in a $name => $type setup. For a full
list of field types you can choose from check the documentation for
`\Cactus\Field`. MySQL is the only supported database as this point, but if
you need support for other database feel free to contribute!

## Relationships

Building relationships with Cactus is easy. Within your Model class you will
need to add information to the `relationships` configuration array. In the ModelUser
example above we specified a relationship using the following.

``` php
<?php
'relationships' => array(
	// Roles
	'role' => array(
		'type' => \Cactus\Relationship::HAS_MANY,
		//'loading' => \Cactus\Loading::EAGER,
		'driver' => "ModelUserRole",
		'column' => 'user_id'
	)
),
```

The relationships array keys (in our case `role`) are the property names that will
be set on the entity. The configuration array for each key can have the following
options.

 Key | Type | Description | Required
-----|------|-------------|----------
type | `string` | The type of relationship we are forming | Yes
driver | `string` | The name \Cactus\Model class that is used to get the relationship | Yes
column | `string` | The name of the column to join the tables on | Yes
loading | `int` | The type of loading to use | No (defaults to Lazy loading)

There are only 2 types of relationships in Cactus, `\Cactus\Relationship::HAS_MANY`
and `\Cactus\Relationship::HAS_ONE`.

For loading you can use either `\Cactus\Loading::LAZY` or `\Cactus\Loading::EAGER`.
Cactus loads relationship using the lazy method by default. Eagerly loaded
relationships are loaded in a way to avoid the N+1 select problem.

## Building Queries

You can build queries for custom methods in your model anyway you want. Cactus
comes bundled with [Peyote](https://github.com/daveWid/Peyote) if you would like to
build your queries in an object oriented way.

## Supported Adapters

Below is a list of currently supported adapters. If you don't see your framework
in the list, hack the code and send a pull request.

* PDO
* Kohana

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
