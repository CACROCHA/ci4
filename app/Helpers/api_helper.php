<?php

use App\Models\XptoModel;
use App\Models\XptoVendasModel;
use App\Models\XptoComprasModel;
use Config\Services;

function getProdutos(array $prm = null) {
    $model = new XptoModel();
    if (!is_null($prm)) {
        $prm = $prm['id'];
        $result = $model->getProdutoUK($prm);
    } else $result = $model->getProdutos();

    foreach ($result as $key => $value) {
        foreach ($value as $nme => $vlr) {
            $value[$nme] = (is_numeric($vlr)) ? (int) $vlr : $vlr;
        }
        $result[$key] = $value;
    }
    return $result;
}

function putProdutos(array $prm) {
    $model = new XptoModel();
    $data = [
        'descricao' => $prm['descricao'],
        'qtde' => $prm['qtde']
    ];
    return $model->putProdutos($data);
}

function updtProdutos(array $prm) {
    $model = new XptoModel();
    $idProduto = (int) $prm['produto'];
    $result = $model->getProdutoUK($idProduto);
    $qtdeAtual = (int) $result[0]['qtde'];
    $qtdeUpdt  = (int) $prm['qtde'];
    $qtdeNova  = $qtdeAtual + $qtdeUpdt;
    $data = ['qtde' => $qtdeNova];
    return $model->updtProdutos($data, $idProduto);
}

function putVendas(array $prm) {
    $venda = new XptoVendasModel();
    $model = new XptoModel();
    $data = ['descricao' => $prm['descricao']];

    $idVenda = $venda->insert($data, true);

    $dados = [];
    foreach ($prm['prods'] as $key => $value) {
        $value['id_venda'] = $idVenda;
        $dados[] = $value;
    }
    return $model->putVendas($dados);
}

function putCompras(array $prm) {
    $compra = new XptoComprasModel();
    $model = new XptoModel();
    $data = ['descricao' => $prm['descricao']];

    $idCompra = $compra->insert($data, true);

    $dados = [];
    foreach ($prm['prods'] as $key => $value) {
        $value['id_compra'] = $idCompra;
        $dados[] = $value;
    }
    return $model->putCompras($dados);
}

function transactionID(string $prefixo = 'Id')
{
    $gmdate = Date('Ymdhms');
    $num = (int) $gmdate * 15;
    $idtrasaction = md5(str_replace('=', '', base64_encode(rand($gmdate, $num))));
    return $prefixo."-".$idtrasaction;
}
