<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');
    
    class UserControl{


        public static function getUserLogin($nome, $senha){

            $nome = strtoupper($nome);

            $sql = new SqlOra();

            $ret = $sql->select("SELECT * from ratuser where nome = :nome",
                array(":nome"=>$nome)
            );

            if(sizeof($ret)>0){
                if (password_verify($senha, $ret[0]['SENHA'])) {
                    return $ret = ['user'=>$ret[0], 'novo'=>0];
                }else{
                    if($ret[0]['SENHA']=='123'){
                        return $ret = ['user'=>$ret[0], 'novo'=>1];
                    }
                   
                }
            
                
            }
        }

        public static function novaSenha($nome, $senha){
            $options = ['cost' => 12,];
            $nSenha = password_hash($senha, PASSWORD_BCRYPT, $options);

            try{
                $sql = new SqlOra();

                $sql->update("UPDATE ratuser
                              set senha = :senha
                            where nome = :nome",
                    array(":nome"=>$nome, ":senha"=>$nSenha)
                );
            }catch(Exception $e){
                echo "erro".$e;
            }

        }
    }




?>