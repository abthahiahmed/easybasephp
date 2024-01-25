<h1>EasyBase</h1>
<h3>EasyBase is a simple PHP library to use MySQL Database very easily</h3>

<p>This is something like mongodb syntax :D</p>

These are simple example below : 

Example : To initialize all the cradentials for database connection.

<h2>Example : To initialize all the cradentials for database connection.</h2>
```
	$base = new EazyBase("localhost", "root", "12345678");
```
<br>
Example : To connect with database
<br>
<h2>Example : To connect with database</h2>
```
	if ($base->connect()){
		echo "Connected!<br>";
	}else{
		echo "Not Connected!<br>";
	}
```
<br>
Example : To create database
```
	if ($base->createBase("mytest")){
		echo "Database Created!";
	}else{
		echo "Database Not Created!";
	}
```
<br>
<h2>Example : To create database</h2>
```
	if ($base->createBase("mytest")){
		echo "Database Created!";
	}else{
		echo "Database Not Created!";
	}
```
<br>
<br>
<h2>Example : To create table</h2>
```
	$result = $base->createTable("users", [
		'id' => 'int not null auto_increment',
		'name' => 'varchar(255)',
		'email' => 'varchar(255)',
		'password' => 'varchar(255)',
	],'id');
```
<br>
<br>
<h2>Example : To insert data into table</h2>

```
	$base->insert("users", [
		'name' => 'myname',
		'email' => "email@gmail.com",
		'password' => '87654321'
	]);
```
<br>
<br>
<h2>Example : To select data from table</h2>
```
	$data = $base->select("users", [], [], [], []);
	echo "<pre>";
	print_r($data);
	echo "</pre>";
```
<br>
<br>
<h2>Example : To select data from table with specific column</h2>
```
	$data = $base->select("users", [name, email], [], [], []);
	echo "<pre>";
	print_r($data);
	echo "</pre>";
```
<br>
<br>
<h2>Example : To select data from table with condition</h2>
```
	$data = $base->select("users", [name, email], [], [], [	
		'email' => ['=', 'email@gmail.com'],
	]);
	echo "<pre>";
	print_r($data);
	echo "</pre>";
```
<br>
<br>
<h2>Example : To select data from table with sorting</h2>
```
	$data = $base->select("users", [name, email], ['ASC', 'id'], [], [	
		'email' => ['=', 'email@gmail.com'],
	]);
	echo "<pre>";
	print_r($data);
	echo "</pre>";
```
<br>
<br>
<h2>Example : To select data from table with limitation</h2>
```
	$data = $base->select("users", [name, email], ['ASC', 'id'], [0, 50], [	
		'email' => ['=', 'email@gmail.com'],
	]);
	echo "<pre>";
	print_r($data);
	echo "</pre>";
```
<br>
<br>
<h2>Example : To update data in a table</h2>
```
	$base->update("users", [
		'name' => 'New Name'
	], [
		'name' => ['=', 'Current Name']
	]);
```
<br>
<br>
<h2>Example : To delete data from table</h2>
```php
	// With equal operator (=)
	$base->delete("users", [
		'id' => ['=', 1]]
	]);
	// With in operator (in)
	$base->delete("users", [
		'id' => ['in', [1, 2, 3]]
	]);
```