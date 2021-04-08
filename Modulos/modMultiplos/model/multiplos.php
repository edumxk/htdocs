<?php
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/model/sqlOracle.php');


    if(isset($_POST['action'])){
        if($_POST['action']=='ajusta'){
            echo Multiplos::ajustaMultiplos();
        }elseif($_POST['action']=='fragmenta'){
            echo Multiplos::fragmentaMultiplos();
        }

    }



    class Multiplos{

        function ajustaMultiplos(){
            Multiplos::atualizaMultiplos();
            $sql = new SqlOra();

            $ret = "";
            $ret .= $sql->update("UPDATE kokar.pcprodut
                set multiplo = multiplooriginal
            where codepto = :codDepto", array(":codDepto"=>10000));

            $ret .= "\n";

            $ret .= $sql->update("UPDATE kokar.pcprodfilial p
                set multiplo = (select multiplooriginal from kokar.pcprodut where codprod = p.codprod)
            where p.codprod in (select codprod from kokar.pcprodut where codepto = :codDepto)", array(":codDepto"=>10000));

            return $ret;
        }


        function fragmentaMultiplos(){
            Multiplos::atualizaMultiplos();
            $sql = new SqlOra();

            $ret = "";
            $ret .= $sql->update("UPDATE kokar.pcprodut
                set multiplo = 1
            where codepto = :codDepto", array(":codDepto"=>10000));

            $ret .= "\n";
            
            $ret .= $sql->update("UPDATE kokar.pcprodfilial p
                set multiplo = 1
            where p.codprod in (select codprod from kokar.pcprodut where codepto = :codDepto)", array(":codDepto"=>10000));

            return $ret;
        }

        function atualizaMultiplos(){
            $sql = new SqlOra();

            $ret = "";
            $ret .= $sql->update("UPDATE kokar.pcprodut
                set multiplooriginal = multiplo
                where nvl(multiplooriginal,0) = 0
                and codepto = :codDepto",array(":codDepto"=>10000)
            );

             
        }
    }

?>