<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');

class ATec{
    public $numparecer;
    public $numrat;
    public $data;
    public $parecer;
    public $testes;
    public $codusur;
    public $nome;
    public $procedente;
    public $final;


    public function getATec($numrat){

        $ret = Rat::getATec($numrat);
        //echo json_encode($ret);
        if($ret != null){

            $d = $ret[0]['data'];

            $y = $d[8].$d[9].'/'.$d[5].$d[6].'/'.$d[0].$d[1].$d[2].$d[3];
            $h = $d[11].$d[12].$d[13].$d[14].$d[15].$d[16].$d[17].$d[18];



            $this->numparecer = $ret[0]['numparecer'];
            $this->numrat = $ret[0]['numrat'];
            //$this->data = $ret[0]['data'];
            $this->data = $y.' '.$h;
            $this->parecer = strtoupper($ret[0]['parecer']);
            $this->testes = strtoupper($ret[0]['testes']);
            $this->codusur = $ret[0]['codusur'];
            $this->nome = strtoupper($ret[0]['nome']);
            $this->procedente = strtoupper($ret[0]['procedente']);
            $this->final = $ret[0]['final'];
        }
        
    }

    public function getSituacao(){
        switch ($this->procedente) {
            case 'S':
                return 'PROCEDENTE';
                break;
            case 'N':
                return 'NÃO PROCEDENTE';
                break;
            case 'P':
                return 'EM ABERTO';
                break;
        }
    }



    
}
?>