<?php

require_once  ($_SERVER["DOCUMENT_ROOT"] . "/Modulos/modMapaProducao/boot.php");
           
    //Controle geral do Sistema de Mapa de Produção
    if(isset($_POST['action'])){

        if($_POST['action']=='getProducao'){
            echo json_encode('getProducao');
        }

        if($_POST['action']=='excluir'){
            echo json_encode(Model::excluir( $_POST['query']['codproducao'], $_POST['query']['codfun'] ));
        }
        if($_POST['action']=='sair'){
            session_start();
            echo session_destroy();
        }

        if($_POST['action']=='cadastrar'){
            $dados = Controle::getProducao($_POST['query']);
            echo json_encode( Model::cadastrar($dados) );
        }

        if($_POST['action']=='cadastrarPorLote'){
            $dados = Controle::getProducao($_POST['query']);
            echo json_encode( Model::consultaLote($dados) );
        }

        if($_POST['action']=='editar'){
            $dados = Controle::getProducao($_POST['query']);
            $banco = Model::getProducaoEditar($dados->codProducao);
            $banco->produtos = Model::consultaLote($dados);
            echo json_encode((Controle::editar($dados, $banco)));
        }

        if($_POST['action']=='getDadosAtualizados'){
            echo json_encode(Controle::getDadosAtualizados());
        }

        if($_POST['action']=='updateStatus'){
            //criar um serviço de updade dos status sem page reload
        }

        if($_POST['action']=='fechaProducao'){
            //criar um serviço de checar se a op foi apontada e atualizar sem page reload.
        }
    }

class Controle{
    
    static function getProducao($dados){
        
        $produtos = Controle::getProduto($dados['produtos']);
        if(isset($dados['dtabertura']))
            $dados['dataini'] = Formatador::formatador2($dados['dtabertura']);

        if(isset($dados['hrabertura']))
            return new Producao( $dados['codproducao'], $dados['categoria'], $dados['codtanque'], $produtos,
                $dados['status'], $dados['lote'], '', $dados['dataini'], $dados['hrabertura'],  Formatador::formatador2($dados['dtfecha']),
                $dados['hrfecha'],  Formatador::formatador2($dados['dtprevisao']),  $dados['hrprevisao'], $dados['codfun'] );
        else{
            return new Producao( $dados['codproducao'], $dados['categoria'], $dados['codtanque'], $produtos,
                $dados['status'], $dados['lote'], '', $dados['dataini'], '',  Formatador::formatador2($dados['dtfecha']),
                $dados['hrfecha'],  Formatador::formatador2($dados['dtprevisao']),  $dados['hrprevisao'], $dados['codfun'] );
        }
    }

    static function getProduto($dados){
        $produtos=[];
        foreach($dados as $p){

            if($p["codprod"] != 'NaN' && $p["op"] == '' )
                $produtos[]= new Produtos( '', $p["codprod"], '', $p["qt"], '', '' );
            else
                $produtos[]= new Produtos( '', $p["codprod"], $p["op"], $p["qt"], '', '' );
        }
        return $produtos;
    }

    static function editar( $dados, $banco ){

        if( $dados->lote != $banco->lote ){//Edição do Lote
            /*Na edição de lote, os produtos devem ser apagados e cadastrado os produtos da OP.
                O lote pode ser editado. Após informar o lote fica proibido a alteração de produtos.
                É possivel remover o lote, mas precisa salvar primeiro para poder Editar os produtos.
            .*/
            
            if( $banco->produtos[0]->codproducao != NULL || $banco->produtos[0]->codprod == NULL){
                Model::removeLote($dados);
                return "Producao ja cadastrada com o lote informado! Ou lote incorreto.";
            }

            $dados->produtos = Model::consultaLote($dados);
            //if( $dados->produtos[0]->codproducao != null)
            if($dados->lote != '')
                echo json_encode( Model::cadastrarProdutos($dados) );
            else
                echo json_encode( Model::removeLote($dados) );

            echo "Update Lote: Mapa: $dados->lote | Banco: $banco->lote \n\n";

        }

        if( $dados->dtPrevisao != $banco->dtPrevisao || $dados->hrPrevisao != $banco->hrPrevisao ){ //Alterar somente a data de Previsao. 

            Model::updateDatas($dados, 'previsao');
            echo "\nUpdate Previsao: Mapa: $dados->dtPrevisao $dados->hrPrevisao | Banco: $banco->dtPrevisao $banco->hrPrevisao \n\n";

        }

        if( $dados->dtAbertura != $banco->dtAbertura ){ //Alterar somente a data de Abertura. 

            Model::updateDatas($dados, 'abertura');
            echo "\nUpdate Abertura: Mapa: $dados->dtAbertura | Banco: $banco->dtAbertura \n\n";

        }

        if( $dados->status != $banco->status ){//atualiza status
            $dados->produtos = Model::consultaLote($dados);
            Model::updateStatus($dados);
            echo "Update Status: Mapa: $dados->status | Banco: $banco->status \n\n";

        }

        if( $dados->tanque != $banco->tanque ){//atualiza status

            Model::updateTanque($dados);
            echo "Update Tanque: Mapa: $dados->tanque | Banco: $banco->tanque \n\n";

        }

        if( ( $dados->dtFecha != $banco->dtFecha || $dados->hrFecha != $banco->hrFecha ) && ( $dados->lote == $banco->lote ) && $banco->lote != ''){ //válido enquanto o lote não for informado.
           
            Model::updateDatas($dados, 'fechamento');
            echo "\nUpdate DT/HR fechamento de OP  $dados->dtFecha / $banco->dtFecha  $dados->hrFecha / $banco->hrFecha \n\n";

        }
        
        if( $dados->lote == $banco->lote && $banco->lote == '' ){//atualiza produtos
            /* 
            Alterar Produtos só é possivel se no banco de dados não possuir lote.
                Em caso de edição, os produtos deveram ser excluidos e adicionado os novos produtos, visando menos dados armazenados.
                Não tem a necessidade de salvar logs de exclusão.
            */
            $contMapa = sizeof($dados->produtos);
            $contBanco = sizeof($banco->produtos);

            if($contMapa == $contBanco and $contMapa > 0){
                
                for($i = 0; $i < $contMapa; $i++ ){
                    if ( $dados->produtos[$i]->codprod != $banco->produtos[$i]->codprod || $dados->produtos[$i]->qt != $banco->produtos[$i]->qt )
                        Model::cadastrarProdutos($dados);
                }
            }
            echo "\nAlterar Produtos\n\n";

        }

    }

    static function getProducaoFeed($data){ //Busca os Cabeçalhos do mapa de producao
        $prod = Model::getProducaoFeed($data);
        return ($prod);
    }

    static function getLinhas(){
        $arr = Model::getLinhas();
        $ret = [$arr[0]["LINHA"],$arr[1]["LINHA"],$arr[2]["LINHA"],$arr[3]["LINHA"],$arr[4]["LINHA"]];
        return ($ret );
    }

    static function getItem(){
        $arr = Model::getItem();
        return ($arr);
    }

    static function getItemH(){
        $arr = Model::getItemH();
        return ($arr);
    }

    static function getProdutosLista(){
        $arr = Model::getProdutosLista();
        return ($arr);
    }

    static function getProducaoE($codproducao){
        $head = Model::getProducaoEditar($codproducao);
        $lote = $head[0]->lote;
        $produtos = Model::getItemLote($codproducao, $lote);
    
        $arr = [$head, $produtos];
        return $arr;
    }

    static function getDadosAtualizados(){
        return Model::atualizaProducao();
    }

}