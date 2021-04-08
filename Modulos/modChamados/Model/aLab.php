<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/modulos/modChamados/dao/daoRat.php');

class ALab{
    //Tabela ratALab
    public $id;
    public $numrat;
    public $data;
    public $parecer;
    public $codpatologia;
    public $coduser;
    public $conforme;
    public $final;

    //Inner join de outras tabelas
    public $nome;   //ratUser
    public $patologia; //ratPatologia


    public function getALab($numrat){
        $ret = Rat::getAlab($numrat);
        
        if($ret != null){
            $row = $ret[0];
            $this->id = $row['ID'];
            $this->numrat = $row['NUMRAT'];
            $this->data = $row['DATA'];
            $this->parecer = utf8_encode($row['PARECER']);
            $this->codpatologia = $row['CODPATOLOGIA'];
            $this->coduser = $row['CODUSER'];
            $this->conforme = $row['CONFORME'];
            $this->final = $row['FINAL'];
            $this->nome = $row['NOME'];   
            $this->patologia = utf8_encode($row['PATOLOGIA']); 
        }

    }





    /*Old
    public function getALab($numrat){

        $ret = Rat::getALab($numrat);
        //echo json_encode($ret);
        if($ret != null){
            
            $d = $ret[0]['data'];

            $y = $d[8].$d[9].'/'.$d[5].$d[6].'/'.$d[0].$d[1].$d[2].$d[3];
            $h = $d[11].$d[12].$d[13].$d[14].$d[15].$d[16].$d[17].$d[18];

            $this->numparecer = $ret[0]['numparecer'];
            $this->numrat = $ret[0]['numrat'];
            //$this->data = $ret[0]['data'];
            $this->data = $y.' '.$h;
            $this->codpatologia = $ret[0]['codpatologia'];
            $this->parecer = strtoupper($ret[0]['parecer']);
            $this->codusur = $ret[0]['codusur'];
            $this->nome = strtoupper($ret[0]['nome']);
            $this->procedente = strtoupper($ret[0]['procedente']);
            $this->final = $ret[0]['final'];
        }
        
    }

    public function getPatologia(){
        $arr = Rat::getPatologia($this->codpatologia);

        if(sizeof($arr)>0){
            return strtoupper($arr[0]['patologia']);
        }else{
            return null;
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
    }*/



    
}






?>