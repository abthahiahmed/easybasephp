<?php
	include 'eazybase.php';

	$base = new EazyBase("localhost", "root", "12345678");

	if ($base->connect()){
		echo "Connected!<br>";
	}else{
		echo "Not Connected!<br>";
	}

	// if ($base->createBase("mytest2")){
	// 	echo "Database Created!";
	// }else{
	// 	echo "Database Not Created!";
	// }
	// echo $base->getError();

	$base->selectBase("mytest");

	// $base->changeColumn("users", [
	// 	'fullname' => 'varchar(1024)',
	// 	'v_code' => 'varchar(1024)',
	// ]);


	// if ($base->importToBase("./turf_booking.sql")){
	// 	echo "Database imported!";
	// }else{
	// 	echo "Database not imported!";
	// }

	// $result = $base->createTable("products", [
	// 	'id' => 'int not null auto_increment',
	// 	'name' => 'varchar(255)',
	// 	'price' => 'double',
	// 	'category' => 'varchar(255)',
	// 	'date' => 'varchar(255)'
	// ],'id');


	// if ($result){
	// 	echo "Table Created!";
	// }else{
	// 	echo "Table Not Created!";
	// }

	// for ($i = 0; $i < 50; $i++){

	// 	$base->insert("users", [
	// 		'name' => 'abtahi'.$i,
	// 		'email' => "email$i@gmail.com",
	// 		'password' => '87654321'.$i
	// 	]);
	
	// }

	// $base->insertMore("users", [
	// 	[
	// 		'name' => 'Jannat',
	// 		'email' => "email5@gmail.com",
	// 		'password' => '87654321'
	// 	],
	// 	[
	// 		'name' => 'Saima',
	// 		'email' => "email6@gmail.com",
	// 		'password' => '87654321'
	// 	],
	// 	[
	// 		'name' => 'Arifa',
	// 		'email' => "email7@gmail.com",
	// 		'password' => '87654321'
	// 	],
	// 	[
	// 		'name' => 'Trisha',
	// 		'email' => "email8@gmail.com",
	// 		'password' => '87654321'
	// 	],
	// ]);
	// echo "Last ID : " . $base->lastInsertID();


	// $base->delete("users", [
	// 	'id' => ['in', [19, 20, 21, 22]]
	// ]);

	// $data = $base->select("users", [], ['ASC', 'id'], [0, 60], [
	// 	'or' => [
	// 		'name' => ['=', 'Abthahi'],	
	// 		'email' => ['=', 'email30@gmail.com'],	
	// 	],
	// 	'and' => [
	// 		'name' => ['=', 'Abthahi'],	
	// 		'email' => ['=', 'email30@gmail.com'],	
	// 	],
	// 	'password' => ['!=', '1234567890'],
	// ]);
	// echo "<pre>";
	// print_r($data);
	// echo "</pre>";

	// $data = $base->selectAsJSON("users", [], ['DESC', 'id'], [0, 60], []);
	// echo "<pre>";
	// echo $data;
	// echo "</pre>";

	// $base->update("users", [
	// 	'name' => 'Abthahi'
	// ], [
	// 	'name' => ['=', 'abtahi16']
	// ]);

	// echo "Last ID : " . $base->lastInsertID();

	