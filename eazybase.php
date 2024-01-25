<?php
	
	

	class EazyBase{
		private $host;
		private $user;
		private $pass;
		private $name;
		private $db;

		static function C_EQUAL($key, $value){
			return array("$key = ?" => $value)[0];
		}
		static function C_LIKE($key, $value){
			return [
				"$key LIKE ?" => $value
			];
		}

		static function C_NEQUAL($key, $value){
			return [
				"$key != ?" => $value
			];
		}



		function __construct($host, $user, $pass){
			$this->host = $host;
			$this->user = $user;
			$this->pass = $pass;
		}




		function connect(){
			$this->db 	= new mysqli($this->host, $this->user, $this->pass);
			return $this->db ? true : false;
		}


		function createBase($name){
			if ($this->db->query("CREATE DATABASE $name")){
				$this->name = $name;
				return true;
			}
			return false;
		}

		function selectBase($name){
			$this->db->select_db($name);
			$this->name = $name;
		}


		function createTable($name, $data, $primary){
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

		function select($name, $field, $sort, $limit, $condition){

			if (!is_array($field)) return false;
			if (!is_array($condition)) return false;
			if (!is_array($sort)) return false;
			if (!is_array($limit)) return false;

			$values = [];

			$columns = array_keys($condition);

			$format_str = '';

			for ($i = 0; $i < count($columns); $i++){
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
			}



			$format_str 	= substr($format_str, 0, -4);

			$where_sql 		= '';
			if (count($columns) > 0) $where_sql = "WHERE $format_str";


			$sql = "UPDATE $name SET $data_str $where_sql";
			
			$stmt = $this->db->prepare($sql);

			if ($stmt->execute($values)){
				$result = $stmt->get_result();
				$data = $result->fetch_all(MYSQLI_ASSOC);
				return $data;
			}

			return false;
		}



		function delete($name, $condition){

			if (!is_array($condition)) return false;
			$values = [];

			$columns = array_keys($condition);

			$format_str = '';

			for ($i = 0; $i < count($columns); $i++){
				$column = $columns[$i];
				$data = $condition[$column];
				$opt = $data[0];

				$isArray = is_array($data[1]);

				$value = $data[1];

				if ($isArray){
					$q = substr(str_repeat('?,', count($value)), 0, -1);
					$format_str .= "$column $opt ($q) AND";
					$values = array_merge($values, $value);
				}else{
					$format_str .= "$column $opt ? AND";
					array_push($values, $value);
				}
				
			}

			$format_str 	= substr($format_str, 0, -3);

			$sql = "DELETE FROM $name WHERE $format_str";
			
			$stmt = $this->db->prepare($sql);

			if ($stmt->execute($values)) return true;

			return false;
		}	

		function lastInsertID(){
			return $this->db->insert_id;
			return false;
		}

		

	}