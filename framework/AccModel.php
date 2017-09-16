<?php
/**
 * Model class
 */
class AccModel
{
	private $dbErrors = array();

	/**
	 * @return string, containing posible error on query
	 */
	public function getdbError(){
		return isset($this->dbErrors[2])? $this->dbErrors[2] : '';
	}

	/**
	 * @return array, array containing object of all models
	 */
	public function findAll(){
		// Do SELECT from a table
		$table = addslashes($this->tableName());
		$sql = "SELECT * FROM `$table`;";
		$query = Acc::db()->prepare($sql);
		$query->execute();
		$models = $query->fetchAll();
		$this->dbErrors = $query->errorInfo();

		return $models;
	}

	/**
	 * @param id inreger, ID of the model we want to get
	 * @return object, the model that was found
	 */
	public function findById($unsafe_id){
		// Making sure that the id is a integer
		$id = intval($unsafe_id);

		// Do SELECT from a table
		$table = addslashes($this->tableName());
		$sql = "SELECT * FROM `$table` WHERE `id`=:id;";
		$query = Acc::db()->prepare($sql);
		$query->execute(array(':id' => $id));
		$model = $query->fetch();
		$this->dbErrors = $query->errorInfo();

		return $model;
	}

	/**
	 * @param attributes array, array with column name and data to search for
	 * @return array, array containing the matching models
	 */
	public function findAllByAttributes($attributes){

		$whereState = '';
		$params = array();
		foreach($attributes as $key => $value){
			$whereState .= "`$key` = :$key AND ";
			$params[":$key"] = $value;
		}
		// We are removing the last five characters since they contain " AND " and we don't want that
		$whereState = substr($whereState, 0, -5);

		$table = addslashes($this->tableName());
		$sql = "SELECT * FROM `$table` WHERE $whereState";
		$query = Acc::db()->prepare($sql);
		$query->execute($params);
		$model = $query->fetchAll();
		$this->dbErrors = $query->errorInfo();

		return $model;	
	}

	/**
	 * @param attributes array, array with column name and data to search for
	 * @return object, model of the matching data
	 */
	public function findByAttributes($attributes){

		$whereState = '';
		$params = array();
		foreach($attributes as $key => $value){
			$whereState .= "`$key` = :$key AND ";
			$params[":$key"] = $value;
		}
		// We are removing the last five characters since they contain " AND " and we don't want that
		$whereState = substr($whereState, 0, -5);

		$table = addslashes($this->tableName());
		$sql = "SELECT * FROM `$table` WHERE $whereState";
		$query = Acc::db()->prepare($sql);
		$query->execute($params);
		$model = $query->fetch();
		$this->dbErrors = $query->errorInfo();

		return $model;	
	}

	/**
	 * @param attributes array, array with all data that should be updated
	 * @return object, the model that was updated
	 */
	public function update($attributes){
		// We do not allow id to be manipulated
		unset($attributes['id']);

		$setState = '';
		$params = array();
		foreach($attributes as $key => $value){
			$setState .= "$key = :$key, ";
			$params[":$key"] = $value;
		}
		// We are removing the last two characters since they contain ", " and we don't want that
		$setState = substr($setState, 0, -2);

		$table = addslashes($this->tableName());
		$sql = "
			UPDATE `$table` SET
				$setState
			WHERE 
				id = :id
		";
		// Adding params id so we know we update the right row
		$params[':id'] = $this->id;
		$query = Acc::db()->prepare($sql);
		$model = $query->execute($params);
		$this->dbErrors = $query->errorInfo();
		return $model;
	}

	/**
	 * @param attributes array, array with all data that should be saved
	 * @return object, the model that was saved
	 */
	public function create($attributes){
		// We do not allow id to be manipulated
		unset($attributes['id']);

		$queryColumns = '';
		$queryHolder = '';
		$params = array();
		foreach($attributes as $key => $value){
			$queryColumns .= $key . ', ';
			$queryHolder .= '?, ';
			$params[] = $value;
		}
		// We are removing the last two characters since they contain ", " and we don't want that
		$queryColumns = substr($queryColumns, 0, -2);
		$queryHolder = substr($queryHolder, 0, -2);

		$table = addslashes($this->tableName());
		$sql = "INSERT INTO `$table` ($queryColumns) VALUES ($queryHolder)";
		$query = Acc::db()->prepare($sql);
		$wasExecuted = $query->execute($params);

		$this->dbErrors = $query->errorInfo();

		if($wasExecuted){
			$this->id = Acc::db()->LastInsertId();// The ID
		}
		return $wasExecuted;
	}

	/**
	 * Delete a model
	 * @return boolean, the model that was found
	 */
	public function delete(){
		$table = addslashes($this->tableName());
		$sql = "DELETE FROM `$table` WHERE `id`=:id;";
		$query = Acc::db()->prepare($sql);
		$query->bindParam(':id', $this->id, PDO::PARAM_INT);
		$status = $query->execute();
		$this->dbErrors = $query->errorInfo();

		return (bool) $status;
	}
}