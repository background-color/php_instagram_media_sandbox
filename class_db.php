<?php
/* --------------------------------------------------------
 DB Class
-------------------------------------------------------- */
class DataBaseModel {
	private $db;

	public function __construct(){
		$this -> dbconnect();
	}

	/* --------------------------------------------------------
		DB接続
	-------------------------------------------------------- */
	private function dbconnect(){

		try {
			$this -> db = new PDO(
				"mysql:dbname=" . DB_NAME . ";host=" . DB_HOST . ";charset=utf8",
				DB_USER,
				DB_PASS,
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
				);
		}catch(PDOException $e) {
				die($e->getMessage());
		}
	}

	/* --------------------------------------------------------
		SELECT
	-------------------------------------------------------- */
	public function find($table, $select = "*", $where = false){
		$query = "SELECT {$select} FROM {$table}";

		if($where)		$query .= " WHERE {$where}";
		$query .= " LIMIT 1";

		try {
			$stmt =  $this->db->prepare($query);
      $stmt->execute();
		}catch(PDOException $e) {
			die($e->getMessage());
		}
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

	/* --------------------------------------------------------
		UPDATE
	-------------------------------------------------------- */
  public function upd($table, $updList, $where){
    if(!$updList || !$table || !$where)  return;

    $setSqlList    = array();

    foreach($updList as $column => $value){
      $setSqlList[]  = "{$column} =  :{$column}";
    }

    $updSql   = "UPDATE {$table} SET ";
    $updSql  .= implode(",", $setSqlList);
    $updSql  .= " WHERE " . $where;

    //$this -> logger -> debug('SQL: ' . $updSql);

    $stmt = $this -> db -> prepare($updSql);

    foreach($updList as $column => $value){
      $stmt->bindValue(":" . $column, $value);
    }

    try {
      $stmt -> execute();
    }catch(PDOException $e) {
      die($e->getMessage());
    }
    return true;
  }

	function close(){
		 mysql_close($this->conid);
	}
	function begin(){
		mysql_query("START TRANSACTION") ? true : false;
	}
	function rollback(){
		mysql_query("ROLLBACK") ? true : false;
	}
	function commit(){
		mysql_query("COMMIT") ? true : false;
	}
	function getErr(){
		return $this->err;
	}
	// ----------------------------------------------------
	// クエリ処理 // 単純実行
	// ----------------------------------------------------
	function query($query){
		try {
			$stmt =  $this->db->query($query);
		}catch(PDOException $e) {
			die($e->getMessage());
		}
    return $stmt;
	}
}
?>
