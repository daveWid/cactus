# DataMapper

## Using

The DataMapper module separates the database communication layer with the data object.

You will need to create a model that extends DataMapper and an object that extends the DataMapper_Object class.

Below is an example using a `Model_User` and a `User` object.

### [DataMapper]

~~~
class Model_User extends DataMapper
{
	// the database table name
	protected $_table = "user";

	// The column that is is primary key
	protected $_primary_key = "user_id";

	// The columns that are in the database
	protected $_columns = array('user_id','email','password','first_name', 'last_name');

	// The name of the DataMapper_Ojbect based class to use when getting the data
	protected $_object_class = "User";
}
~~~

### [DataMapper_Object]

~~~
class User extends DataMapper_Object
{
	// Set the object validation rules here
	protected function _validation_rules(Validation $valid)
	{
		return $valid->rule('email', 'not_empty')
			->rule('email', 'email')
			->rule('password', 'not_empty')
			->rule('first_name', 'not_empty')
			->rule('last_name', 'not_empty');
	}
}
~~~

### Creating a Record

~~~
$model = new Model_User;

// No primary key passed to get returns a new object
$user = $model->get();

// $user is now a User object.
$user->set(array(
	'email' => "test@example.com",
	'password' => "password",
	'first_name' => "Foo",
	'last_name' => "Bar"
));

$model->save($user);

// Or
// $model->create($user);
~~~

### Updating a Record

~~~
// Find the user with a user id of 1
$model = new Model_User;
$user = $model->get(1);

$user->last_name = "Baz";
$model->save($user);

// Or
// $model->update($user);
~~~

### Deleting a Record

~~~
// Another way to get the Model_User class
$model = Model::factory('user');

$model->delete($model->get(1));
~~~

## By Reference

The object that is passed into the `save`, `create`, `update` and `delete` methods is passed by reference, so it will modified by these functions internally.

## Dive Deeper

Please take a look at the api browser for [DataMapper] and [DataMapper_Object] for more details on class usage.

## Contributing

See missing features??? Jump in and get hacking!

### Github

All code will is hosted on [GitHub](https://github.com/daveWid/datamapper).

### Development Model

Development will follow the the [git branching](http://nvie.com/posts/a-successful-git-branching-model/)
model that is followed by Kohana.

[!!] When contributing, make sure you are using the _version_/develop branch!!

---

Developed by [Dave Widmer](http://www.davewidmer.net)