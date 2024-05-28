<?php

class Km{

    public $placa;
    public $kmini;
    public $kmfinal;
    public $kmrodado;
    public $litragem;
    public $litragemArla;
    public $kmlitro;
    public $valorlitro;
    public $valor;

    public static function tratamentoDados($dados, $placas){
        $dado =[''];
        //'codprojeto','valor','dados','historico2','competencia','projeto','codveiculo','descricao','placa','marca'
        foreach($placas as $pl1){
            foreach($dados as $d){
                if($pl1->placa == $d['placa'])
                    array_push($dado, ['placa' => $d['placa']
                    ,'km' => $d['dados']['km']
                    ,'litragem' => $d['dados']['litragem']
                    ,'valor' => $d['dados']['valor']]);
                else
                array_push($dado, ['placa' => $pl1->placa
                ,'km' => 0
                ,'litragem' => 0
                ,'valor' => 0 ]);
           } 
        }

       return Km::agruparDados(array_filter($dado));
       
    }

    public static function agruparDados($dados) {
        $agrupados = array_reduce($dados, function($resultado, $item) {
            $placa = $item['placa'];
            
            if (!isset($resultado[$placa])) {
                    $resultado[$placa] = [
                        'placa' => $placa,
                        'kmini' => $item['km'],
                        'kmfinal' => $item['km'],
                        'litragem' => $item['litragem'],
                        
                        'valor' => $item['valor']
                    ];
            } else {
                $resultado[$placa]['kmini'] = min($resultado[$placa]['kmini'], $item['km']);
                $resultado[$placa]['kmfinal'] = max($resultado[$placa]['kmfinal'], $item['km']);
                $resultado[$placa]['litragem'] += $item['litragem'];
                $resultado[$placa]['valor'] += $item['valor'];
            }
            
            return $resultado;
        }, []);

        return array_values($agrupados);
    }

    public static function tratamentoKms($dados){
        $temp = [];
        $kms = [];
        foreach($dados as $dado){
            foreach($dado as $d){
                array_push($temp, $d);
            }
            $kms[] = $temp;
            $temp = [];
        }
        return $kms;
    }

}