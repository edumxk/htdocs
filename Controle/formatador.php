<?php 

    class Formatador{

        public static function limpaString($inp) {
            //Função para limpar caracteres de banco de dados de uma string.
            if(is_array($inp))
                return array_map(__METHOD__, $inp);
        
            if(!empty($inp) && is_string($inp)) {
                return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
            }
        
            return $inp;
        }

        public static function stringPesquisa($texto){
            $replaceSimbolos = array("-","."," ");
            $texto = strtoupper($texto);
            $texto = str_replace($replaceSimbolos, "%", $texto);
            $texto = str_replace("Ç", "C", $texto);
            $texto = str_replace("Ã", "A", $texto);
            $texto = str_replace("Õ", "O", $texto);
            $texto = str_replace("Á", "A", $texto);
            $texto = str_replace("É", "E", $texto);
            $texto = str_replace("Í", "I", $texto);
            $texto = str_replace("Ó", "O", $texto);
            $texto = str_replace("Ú", "U", $texto);
            $texto = "%".$texto."%";

            return $texto;
        }


        public static function formatarData($data){
            if($data == null){
                return null;
            }
            $dex=[];
            if(strpos($data, '/') !== false){
                $dex = Formatador::explodir('/',$data);
            }elseif(strpos($data, '-') !== false){
                $dex = Formatador::explodir('-',$data);
            }
            $dia = 0;
            if(strlen($dex[0])==2){
                $dia = $dex[0];
            }else{
                $dia = '0'.$dex[0];
            }

            $mes = 0;
            if(strlen($dex[1])>2){
                $mes = date('m', strtotime($dex[1]));
            }elseif(strlen($dex[1])==1){
                $mes = '0'.$dex[1];
            }else{
                $mes = $dex[1];
            }

            $ano = 0;
            if(strlen($dex[2])>=3){
                $ano = $dex[2];
            }elseif(strlen($dex[2])==2){
                if($dex[2]>date('y')){
                    $ano = '19'.$dex[2];
                }else{
                    $ano = '20'.$dex[2];
                }

            }
            //$ano = intval(date('Y', strtotime($dex[2])));
    
            $dataFinal = $dia.'/'.$mes.'/'.$ano;
    
            return $dataFinal;
        
        }

        function dataFormatUs($data){
            $arr = explode('/',$data);
            $ret = $arr[2].'/'.$arr[1].'/'.$arr[0];
            return $ret;
        }

        function explodir($divisor, $string){
            return explode($divisor, $string);
        }


    }




?>