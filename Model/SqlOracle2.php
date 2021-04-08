<?php


class SqlOra extends PDO{
    
    private $conn;

    public function __construct(){

        $tns = "  
        (DESCRIPTION =
            (ADDRESS_LIST =
            (ADDRESS = (PROTOCOL = TCP)(HOST = 172.168.1.25)(PORT = 1521))
            )
            (CONNECT_DATA =
            (SERVICE_NAME = WINT)
            )
        )
            ";
        $db_username = "PARALELO";
        $db_password = "PARALELO";

        $this->conn = new PDO("oci:dbname=".$tns.";charset=UTF8",$db_username,$db_password);
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
        echo json_encode($stmt->fetchAll());
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    // public function select($rawQuery, $params = array()):array{
    //     $stmt = $this->query($rawQuery, $params);
    //     $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     $retorno = [];
    //     if(sizeof($ret)>0){
    //         foreach($ret[0] as $key=>$val){
    //             array_push($retorno, array(utf8_encode($key)=>utf8_encode($val)));
    //         }
    //     }
    //     return $retorno;
    // }



    public function insert($query, $params){
        try{
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return 'ok';
        }catch(PDOException $e) {
            return 'Erro: ' . $e->getMessage();
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

    public function update($query, $params){
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