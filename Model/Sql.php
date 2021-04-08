<?php

class Sql extends PDO{
    
    private $conn;
    public function __construct(){
        $this->conn = new PDO("mysql:dbname=dbchamados;host=localhost","root","");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function setParam($statement ,$key, $value){
        $statement->bindParam($key, $value);
    }

    private function setParams($statement, $parameters = array()){
        foreach ($parameters as $key => $value){
            $this->setParam($statement, $key, $value);
        }
    }

    public function query($rawQuery, $params = array()){
        $stmt = $this->conn->prepare($rawQuery);
        $this->setParams($stmt, $params);
        $stmt->execute();
        return $stmt;
    }

    public function select($rawQuery, $params = array()):array{
        $stmt = $this->query($rawQuery, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function insert($query, $params){
        try{
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            
            
            return 'ok';
        }catch(PDOException $e) {
            return 'Error: ' . $e->getMessage();
            return 'erro';
        }
    }

    public function delete($query, $params){
        try{
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            
            
            return 'ok';
        }catch(PDOException $e) {
            return 'Error: ' . $e->getMessage();
            return 'erro';
        }
    }



}

?>