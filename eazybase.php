<?php
	class EazyBase{
		private $host;
		private $user;
		private $pass;
		private $name;
		private $db;

		function __construct($host, $user, $pass){
			$this->host = $host;
			$this->user = $user;
			$this->pass = $pass;
		}

		function connect(){
			$this->db 	= new mysqli($this->host, $this->user, $this->pass);
			return $this->db ? true : false;
		}


		function createBase($name, $charset = 'utf8mb4', $collate = 'utf8mb4_unicode_ci'){
			if ($this->db->query("CREATE DATABASE $name CHARACTER SET $charset COLLATE $collate;")){
				$this->selectBase($name);
				return true;
			}else{
				echo $this->getError();
			}
			return false;
		}

		function selectBase($name){
			if (empty($name)) return false;
			$this->db->select_db($name);
			$this->name = $name;
		}
		function deleteBase($name){
			if (empty($name)) return false;
			$sql = "DROP DATABASE $name;";
			if($this->db->query($sql)) return true;
			return false;
		}


		function createTable($name, $data, $primary){
			if (empty($name)) return false;
			if (!is_array($data)) return false;
			
			$columns 	= array_keys($data);
			$types 		= array_values($data);

			$sql 		= "CREATE TABLE $name(";
			
			for ($i = 0; $i < count($columns); $i++){
				$sql 	.= "{$columns[$i]} {$types[$i]},";
			}

			$sql 		.= "PRIMARY KEY ($primary)";
			$sql 		.= ");";

			if($this->db->query($sql)) return true;
			return false;
		}

		function addColumn($name, $data){
			if (empty($name)) return false;
			if (!is_array($data)) return false;
			
			$columns 	= array_keys($data);
			$types 		= array_values($data);

			$sql 		= "ALTER TABLE $name ";
			
			for ($i = 0; $i < count($columns); $i++){
				$sql 	.= "ADD {$columns[$i]} {$types[$i]},";
			}
			$sql 		= substr($sql, 0, -1);
			$sql 		.= ";";

			if($this->db->query($sql)) return true;
			return false;
		}
		
		function changeColumn($name, $data){
			if (empty($name)) return false;
			if (!is_array($data)) return false;
			
			$columns 	= array_keys($data);
			$types 		= array_values($data);

			$sql 		= "ALTER TABLE $name ";
			
			for ($i = 0; $i < count($columns); $i++){
				$sql 	.= "MODIFY COLUMN {$columns[$i]} {$types[$i]},";
			}
			$sql 		= substr($sql, 0, -1);
			$sql 		.= ";";

			if($this->db->query($sql)) return true;
			return false;
		}


		function deleteColumn($name, $data){
			if (empty($name)) return false;
			if (!is_array($data)) return false;
			
			$columns 	= $data;

			$sql 		= "ALTER TABLE $name ";
			
			for ($i = 0; $i < count($columns); $i++){
				$sql 	.= "DROP COLUMN {$columns[$i]},";
			}
			$sql 		= substr($sql, 0, -1);
			$sql 		.= ";";

			if($this->db->query($sql)) return true;
			return false;
		}

		function renameColumn($name, $data){
			if (empty($name)) return false;
			if (!is_array($data)) return false;
			
			$old 	= array_keys($data);
			$new 		= array_values($data);

			$sql 		= "ALTER TABLE $name ";
			
			for ($i = 0; $i < count($old); $i++){
				$sql 	.= "RENAME COLUMN {$old[$i]} TO {$new[$i]},";
			}
			$sql 		= substr($sql, 0, -1);
			$sql 		.= ";";

			if($this->db->query($sql)) return true;
			return false;
		}


		function deleteTable($name){
			if (empty($name)) return false;
			$sql = "DROP TABLE $name;";
			if($this->db->query($sql)) return true;
			return false;
		}

		function importToBase($filename){
			if (!file_exists($filename)) return false;
			$lines = file($filename);
			$tmpline = '';
			$success = false;
			foreach ($lines as $line){
				if (substr($line, 0, 2) === '--' || $line === ''){
					continue;
				}
				$tmpline .= $line;
				if (substr(trim($line), -1, 1) === ';'){
					if ($this->db->query($tmpline)){
						$success = true;
						
					}else{
						$success = false;
					}
					$tmpline = '';
				}
			}
			return $success;
		}


		function insert($table, $data){

			if (!is_array($data)) return false;

			$columns 	= array_keys($data);
			$values 	= array_values($data);

			$columnStr 	= implode(',', $columns);
			$valuesStr 	= substr(str_repeat('?,', count($values)), 0, -1);

			$sql 		= "INSERT INTO $table ($columnStr) VALUES ($valuesStr)";
			$stmt 		= $this->db->prepare($sql);
			if ($stmt->execute($values)) return true;
			return false;
		}

		function insertMore($table, $data){
			if (!is_array($data)) return false;
			if (count($data) < 1) return false;


			$columns 	= array_keys($data[0]);

			$values 	= [];
			$columnStr 	= implode(',', $columns);
			$sql 		= "INSERT INTO $table ($columnStr) VALUES ";
			

			for ($i = 0; $i < count($data); $i++){
				$value = array_values($data[$i]);
				$values = array_merge($values, $value);

				$valuesStr 	= substr(str_repeat('?,', count($value)), 0, -1);
				$sql        .= "($valuesStr),";
			}
			$sql        = substr($sql, 0, -1);
			
			echo $sql;
			$stmt 		= $this->db->prepare($sql);

			if ($stmt->execute($values)) return true;
			return false;
		}




		function select($name, $field, $sort, $limit, $condition){

			if (!is_array($field)) return false;
			if (!is_array($condition)) return false;
			if (!is_array($sort)) return false;
			if (!is_array($limit)) return false;

			$values = [];



			$columns = array_keys($condition);
			$format_str = '';
		
			for ($i = 0; $i < count($columns); $i++){

				if ($columns[$i] !== 'or' && $columns[$i] !== 'and'){

					$column = $columns[$i];
					$data = $condition[$column];
					$opt = $data[0];

					$isArray = is_array($data[1]);

					$value = $data[1];

					if ($isArray){
						$q = substr(str_repeat('?,', count($value)), 0, -1);
						$format_str .= "$column $opt ($q) AND ";
						$values = array_merge($values, $value);
					}else{
						$format_str .= "$column $opt ? AND ";
						array_push($values, $value);
					}

				}else{
					$inner_columns = array_keys($condition[$columns[$i]]);
					$format_str .= ' (';
					for ($j = 0; $j < count($inner_columns); $j++){

						$column = $inner_columns[$j];
						$data = $condition[$columns[$i]][$column];
						$opt = $data[0];

						$isArray = is_array($data[1]);

						$value = $data[1];

						if ($isArray){
							$q = substr(str_repeat('?,', count($value)), 0, -1);
							$format_str .= "$column $opt ($q) {$columns[$i]} ";
							$values = array_merge($values, $value);
						}else{
							$format_str .= "$column $opt ? {$columns[$i]} ";
							array_push($values, $value);
						}

					}
					$format_str 	= substr($format_str, 0, -4);
					$format_str .= ') AND ';

				}

			}

			$format_str 	= substr($format_str, 0, -4);

			$where_sql = '';
			if (count($columns) > 0) $where_sql = "WHERE $format_str";

			$sort_sql = '';
			if (count($sort) > 0) $sort_sql = "ORDER BY {$sort[1]} {$sort[0]}";

			$limit_sql = '';
			if (count($limit) > 0) $limit_sql = "LIMIT " . implode(',', $limit);

			$field_sql = '*';
			if (count($field) > 0) $field_sql = implode(',', $field);

			$sql = "SELECT $field_sql FROM $name $where_sql $sort_sql $limit_sql";
			echo $sql;
			$stmt = $this->db->prepare($sql);

			if ($stmt->execute($values)){
				$result = $stmt->get_result();
				$data = $result->fetch_all(MYSQLI_ASSOC);
				return $data;
			}

			return false;
		}	



		function selectAsJSON($name, $field, $sort, $limit, $condition){

			if (!is_array($field)) return false;
			if (!is_array($condition)) return false;
			if (!is_array($sort)) return false;
			if (!is_array($limit)) return false;

			$values = [];

			$columns = array_keys($condition);

			$format_str = '';

			for ($i = 0; $i < count($columns); $i++){

				if ($columns[$i] !== 'or' && $columns[$i] !== 'and'){

					$column = $columns[$i];
					$data = $condition[$column];
					$opt = $data[0];

					$isArray = is_array($data[1]);

					$value = $data[1];

					if ($isArray){
						$q = substr(str_repeat('?,', count($value)), 0, -1);
						$format_str .= "$column $opt ($q) AND ";
						$values = array_merge($values, $value);
					}else{
						$format_str .= "$column $opt ? AND ";
						array_push($values, $value);
					}

				}else{
					$inner_columns = array_keys($condition[$columns[$i]]);
					$format_str .= ' (';
					for ($j = 0; $j < count($inner_columns); $j++){

						$column = $inner_columns[$j];
						$data = $condition[$columns[$i]][$column];
						$opt = $data[0];

						$isArray = is_array($data[1]);

						$value = $data[1];

						if ($isArray){
							$q = substr(str_repeat('?,', count($value)), 0, -1);
							$format_str .= "$column $opt ($q) {$columns[$i]} ";
							$values = array_merge($values, $value);
						}else{
							$format_str .= "$column $opt ? {$columns[$i]} ";
							array_push($values, $value);
						}

					}
					$format_str 	= substr($format_str, 0, -4);
					$format_str .= ') AND ';

				}

			}

			$format_str 	= substr($format_str, 0, -4);

			$where_sql = '';
			if (count($columns) > 0) $where_sql = "WHERE $format_str";

			$sort_sql = '';
			if (count($sort) > 0) $sort_sql = "ORDER BY {$sort[1]} {$sort[0]}";

			$limit_sql = '';
			if (count($limit) > 0) $limit_sql = "LIMIT " . implode(',', $limit);

			$field_sql = '*';
			if (count($field) > 0) $field_sql = implode(',', $field);

			$sql = "SELECT $field_sql FROM $name $where_sql $sort_sql $limit_sql";
			
			$stmt = $this->db->prepare($sql);

			if ($stmt->execute($values)){
				$result = $stmt->get_result();
				$data = $result->fetch_all(MYSQLI_ASSOC);
				return json_encode($data);
			}

			return false;
		}	

		function update($name, $data, $condition){
			if (!is_array($data)) return false;
			if (!is_array($condition)) return false;

			$values 	= [];

			$data_str = '';
			$data_columns = array_keys($data);
			$data_values = array_values($data);
			$values = array_merge($values, $data_values);

			for ($i = 0; $i < count($data_columns); $i++){
				$data_str .= "{$data_columns[$i]} = ?,";
			}

			$data_str = substr($data_str, 0, -1);

			$columns = array_keys($condition);

			$format_str = '';

			for ($i = 0; $i < count($columns); $i++){

				if ($columns[$i] !== 'or' && $columns[$i] !== 'and'){

					$column = $columns[$i];
					$data = $condition[$column];
					$opt = $data[0];

					$isArray = is_array($data[1]);

					$value = $data[1];

					if ($isArray){
						$q = substr(str_repeat('?,', count($value)), 0, -1);
						$format_str .= "$column $opt ($q) AND ";
						$values = array_merge($values, $value);
					}else{
						$format_str .= "$column $opt ? AND ";
						array_push($values, $value);
					}

				}else{
					$inner_columns = array_keys($condition[$columns[$i]]);
					$format_str .= ' (';
					for ($j = 0; $j < count($inner_columns); $j++){

						$column = $inner_columns[$j];
						$data = $condition[$columns[$i]][$column];
						$opt = $data[0];

						$isArray = is_array($data[1]);

						$value = $data[1];

						if ($isArray){
							$q = substr(str_repeat('?,', count($value)), 0, -1);
							$format_str .= "$column $opt ($q) {$columns[$i]} ";
							$values = array_merge($values, $value);
						}else{
							$format_str .= "$column $opt ? {$columns[$i]} ";
							array_push($values, $value);
						}

					}
					$format_str 	= substr($format_str, 0, -4);
					$format_str .= ') AND ';

				}

			}

			$format_str 	= substr($format_str, 0, -4);

			$where_sql 		= '';
			if (count($columns) > 0) $where_sql = "WHERE $format_str";


			$sql = "UPDATE $name SET $data_str $where_sql";
			
			$stmt = $this->db->prepare($sql);

			if ($stmt->execute($values)){
				$result = $stmt->get_result();
				// $data = $result->fetch_all(MYSQLI_ASSOC);
				return $result;
			}

			return false;
		}



		function delete($name, $condition){

			if (!is_array($condition)) return false;
			$values = [];

			$columns = array_keys($condition);

			$format_str = '';

			for ($i = 0; $i < count($columns); $i++){

				if ($columns[$i] !== 'or' && $columns[$i] !== 'and'){

					$column = $columns[$i];
					$data = $condition[$column];
					$opt = $data[0];

					$isArray = is_array($data[1]);

					$value = $data[1];

					if ($isArray){
						$q = substr(str_repeat('?,', count($value)), 0, -1);
						$format_str .= "$column $opt ($q) AND ";
						$values = array_merge($values, $value);
					}else{
						$format_str .= "$column $opt ? AND ";
						array_push($values, $value);
					}

				}else{
					$inner_columns = array_keys($condition[$columns[$i]]);
					$format_str .= ' (';
					for ($j = 0; $j < count($inner_columns); $j++){

						$column = $inner_columns[$j];
						$data = $condition[$columns[$i]][$column];
						$opt = $data[0];

						$isArray = is_array($data[1]);

						$value = $data[1];

						if ($isArray){
							$q = substr(str_repeat('?,', count($value)), 0, -1);
							$format_str .= "$column $opt ($q) {$columns[$i]} ";
							$values = array_merge($values, $value);
						}else{
							$format_str .= "$column $opt ? {$columns[$i]} ";
							array_push($values, $value);
						}

					}
					$format_str 	= substr($format_str, 0, -4);
					$format_str .= ') AND ';

				}

			}

			$format_str 	= substr($format_str, 0, -4);

			$sql = "DELETE FROM $name WHERE $format_str";
			
			$stmt = $this->db->prepare($sql);

			if ($stmt->execute($values)) return true;
			else{
				echo $this->getError();
			}

			return false;
		}	

		function lastInsertID(){
			return $this->db->insert_id;
			return false;
		}

		function getError(){
			return $this->db->error;
		}

		function close(){
			$this->db->close();
		}

	}