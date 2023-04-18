<?php

Class Formulario{
    public $numrat;
    public $idPrincipal;
    public $textoPrincipal;
    public $idOpcao;
    public $textoOpcao;
    public $selected;
    public $nome;
    public $email;
    public $telefone;


    public static function getFormulario(array $array){
        if(count($array)==0)
            return '';
            $ret = [];
            $formulario = [];

            foreach($array[0] as $a){
                $f = new Formulario();
                $f->idPrincipal = $a['IDPRINCIPAL'];
                $f->textoPrincipal = utf8_encode($a['TEXTOPRINCIPAL']);
                $ret[] = $f;
        }
        
        $formulario[] = $ret;
        $ret = [];

        foreach($array[1] as $a){
            $f = new Formulario();
            $f->idOpcao = $a['IDOPCAO'];
            $f->idPrincipal = $a['IDPRINCIPAL'];
            $f->textoOpcao = utf8_encode($a['TEXTOOPCAO']);
            $ret[] = $f;
        }
        $formulario[] = $ret;
        return $formulario;
    }

    public static function getFormularioOpcoes(array $array){
        if(count($array)==0)
            return '';
            $ret = [];
            $formulario = [];
            $nome = $array['nome'];
            $email = $array['email'];
            $telefone = $array['telefone'];

            foreach($array['selecoes'] as $k => $a){
                $f = new Formulario();
                $f->numrat = $array['numrat'];
                $f->idOpcao = $a;
                $f->idPrincipal = $k+1;
                $ret[] = $f;
            }
            $formulario = ['nome' => $nome, 'email' => $email, 'telefone' => $telefone, 'selecoes' => $ret];
            return $formulario;
    }

    public static function getFormularioRat(array $array){
        if(count($array)==0)
            return [];
            $formulario = [];

            foreach($array as $a){
                $f = new Formulario();
                $f->numrat = $a['NUMRAT'];
                $f->idOpcao = $a['IDOPCAO'];
                $f->idPrincipal = $a['IDPRINCIPAL'];
                $f->textoOpcao = utf8_encode($a['TEXTOOPCAO']);
                if($f->idOpcao == 0)
                    $f->textoOpcao ='Não Informado';
                $f->textoPrincipal = utf8_encode($a['TEXTOPRINCIPAL']);
                $f->nome = utf8_encode($a['NOME']);
                $f->email = utf8_encode($a['EMAIL']);
                $f->telefone = utf8_encode($a['TELEFONE']);
                $formulario[] = $f;
            }
           
            return $formulario;
    }

}