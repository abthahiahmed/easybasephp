<h1>EasyBase</h1>
<h3>EasyBase is a simple PHP library to use MySQL Database very easily</h3>

<p>This library will minified the code for database operations in web applications. I am working on it. It will be more useful for PHP Web Developers.</p>

These are simple example below : 
<br>
<h2>Example : To initialize all the cradentials for database connection.</h2>

```php
$base = new EazyBase("localhost", "root", "12345678");
```

<br>
<br>
<h2>Example : To connect with database</h2>

```php
if ($base->connect()){
	echo "Connected!<br>";
}else{
	echo "Not Connected!<br>";
}
```

<br>
<br>
<h2>Example : To create database</h2>

```php
if ($base->createBase("mytest")){
	echo "Database Created!";
}else{
	echo "Database Not Created!";
}
```

<br>
<h2>Example : To create database</h2>

```php
if ($base->createBase("mytest")){
	echo "Database Created!";
}else{
	echo "Database Not Created!";
}
```
<br>
<br>
<h2>Example : To create table</h2>

```php
$result = $base->createTable("users", [ // Table Name
	'id' => 'int not null auto_increment', //// Column name => Data type with Auto increament
	'name' => 'varchar(255)', // Column name => Data type
	'email' => 'varchar(255)', // Column name => Data type
	'password' => 'varchar(255)', // Column name => Data type
],'id'); // Primary key
```

<br>
<br>
<h2>Example : To insert data into table</h2>

```php
$base->insert("users", [// Table Name
	'name' => 'myname', //Column => Data
	'email' => "email@gmail.com", //Column => Data
	'password' => '87654321' //Column => Data
]);
```
<br>
<br>
<h2>Example : To insert multiple data into table</h2>

```php
$base->insertMore("users", [ //Table Name
	[
		'name' => 'Ehsan', //Column => Data
		'email' => "email1@gmail.com", //Column => Data
		'password' => '87654321' //Column => Data
	],
	[
		'name' => 'Abthahi', //Column => Data
		'email' => "email2@gmail.com", //Column => Data
		'password' => '87654321' //Column => Data
	],
	[
		'name' => 'Ishmam', //Column => Data
		'email' => "email3@gmail.com", //Column => Data
		'password' => '87654321' //Column => Data
	],
	[
		'name' => 'Sakib', //Column => Data
		'email' => "email4@gmail.com", //Column => Data
		'password' => '87654321' //Column => Data
	],
]);
```

<h2>Example : To select data from table</h2>

```php
$data = $base->select("users", [], [], [], []);
echo "<pre>";
print_r($data);
echo "</pre>";
```
<br>
<br>
<h2>Example : To select data from table with specific column</h2>

```php
$data = $base->select("users", ['name', 'email'], [], [], []);
echo "<pre>";
print_r($data);
echo "</pre>";
```
<br>
<br>
<h2>Example : To select data from table with condition</h2>

```php
$data = $base->select("users", ['name', 'email'], [], [], [	
	'email' => ['=', 'email@gmail.com'], // Column => [Operator, Data]
]);
echo "<pre>";
print_r($data);
echo "</pre>";
```
<br>
<br>
<h2>Example : To select data from table with sorting</h2>

```php
$data = $base->select("users", ['name', 'email'], ['ASC', 'id'], [], [	
	'email' => ['=', 'email@gmail.com'],// Column => [Operator, Data]
]);
echo "<pre>";
print_r($data);
echo "</pre>";
```
<br>
<br>
<h2>Example : To select data from table with limitation</h2>

```php
$data = $base->select("users", 
	['name', 'email'], // Fields
	['ASC', 'id'], // Ordering Column
	[0, 50], // Limit
	[	
	'email' => ['=', 'email@gmail.com'], // Column => [Operator, Data]
]);
echo "<pre>";
print_r($data);
echo "</pre>";
```
<br>
<br>
<h2>To select data with logical operator like and/or : </h2>

```php
$data = $base->select("users", [], ['ASC', 'id'], [0, 60], [
	'or' => [ // Logic Operator
		'name' => ['=', 'Abthahi'],	// Column => [Operator, Data]
		'email' => ['=', 'email30@gmail.com'],	// Column => [Operator, Data]
	],
	'and' => [ // Logic Operator
		'name' => ['=', 'Abthahi'],	// Column => [Operator, Data]
		'email' => ['=', 'email30@gmail.com'],	// Column => [Operator, Data]
	],
]);
echo "<pre>";
print_r($data);
echo "</pre>";
```
<br>
<br>

<h2>Example : To import sql files to newly created database</h2>

```php
if ($base->importToBase("./filename.sql")){
	echo "Database imported!";
}else{
	echo "Database not imported!";
}
```

<br>
<br>
<h2>Example : To update data in a table</h2>

```php
$base->update("users", [
	'name' => 'New Name'// Column => Data
], [
	'name' => ['=', 'Current Name'] // Column => [Operator, Data]
]);
```
<br>
<br>
<h2>Example : To delete data from table</h2>

```php
// With equal operator (=)
$base->delete("users", [
	'id' => ['=', 1]]// Column => [Operator, Data]
]);
// With in operator (in)
$base->delete("users", [
	'id' => ['in', [1, 2, 3]]// Column => [Operator, Data(Array)]
]);
```

This is simple project which can help you to build PHP applications faster. 
Happy Coding...