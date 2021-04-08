<?php 

    include 'dao/vendas.php';



    class VendasControl{
        /*public static function getVendas(){
            $vendas = Vendas::getVendasDia();

            $cargas = array();
            $final = array();

            if(count($vendas)>0){
                for($i=0; $i<count($vendas); $i++){
                    $row = $vendas[$i];
                    if(!in_array($row['NUMCAR'], $cargas)){
                        array_push($cargas, $row['NUMCAR']);
                    }
                }

                for($c=0; $c<count($cargas); $c++){
                    $arr = array();
                    for($i=0; $i<count($vendas); $i++){
                        $row = $vendas[$i];
                        if(in_array($cargas[$c], $row)){
                            array_push($arr, $row);
                        }
                    }
                    array_push($final, [$cargas[$c]=>$arr]);
                }
                return JSON_ENCODE($final);
            }
        }
    
        public static function getTest(){
            return JSON_ENCODE(Vendas::getTest());
        }*/

        public static function getCargasFat($dtfat){
            
            $cargas = Vendas::getCargasDia($dtfat);
            $faturamento = Vendas::getFaturamentosDia($dtfat);
            $pesoMes = Vendas::getPesoMes();

            $return = array('CARGA'=>$cargas, 'FATURAMENTO'=>$faturamento, 'PESOMES'=>$pesoMes);
            return $return;
        }
    
    }



?>