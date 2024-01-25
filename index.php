<?php

	include 'eazybase.php';


	$base = new EazyBase("localhost", "root", "12345678");

	if ($base->connect()){
		echo "Connected!<br>";
	}else{
		echo "Not Connected!<br>";
	}

	// if ($base->createBase("mytest")){
	// 	echo "Database Created!";
	// }else{
	// 	echo "Database Not Created!";
	// }

	$base->selectBase("mytest");

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


	// $base->delete("users", [
	// 	'name' => ['in', ['abtahi13', 'abtahi14', 'abtahi15']]
	// ]);

	// $data = $base->select("users", [], ['ASC', 'id'], [0, 60], [	
	// 	'name' => ['=', 'abtahi20'],
	// 	'email' => ['=', 'email20@gmail.com'],
	
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

	