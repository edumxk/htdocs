<?php 

    class GeralControle{
        public static function nomeRca($nome){
            if (strrpos($nome, 'KOKAR') === 0) {
                return 'KOKAR';
            }else if(strrpos($nome, 'WANDERLEI') === 0) {
                return 'WANDERLEI';
            }else if(strrpos($nome, 'DOMINGOS') === 0) {
                return 'DOMINGOS';
            }else if(strrpos($nome, 'MAURO') === 0) {
                return 'MAURO';
            }else if(strrpos($nome, 'JOILDO') === 0) {
                return 'JOILDO';
            }else if(strrpos($nome, 'FRANKLIN ') === 0) {
                return 'FRANKLIN ';
            }else if(strrpos($nome, 'RADAMES') === 0) {
                return 'RADAMES';
            }else if(strrpos($nome, 'FREDERICO') === 0) {
                return 'FREDERICO';
            }else if(strrpos($nome, 'DALMI') === 1) {
                return 'DALMI';
            }else if(strrpos($nome, 'JEAN') === 0) {
                return 'JEAN';
            }else{
                return $nome;
            }
            

        }


    }

?>