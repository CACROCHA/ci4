<?php

use App\Models\MsgModel;
use Config\Services;

function getMSG($idMSG)
{
    helper('filesystem');
    $localDir   = Services::getLocalDir();
    $nmeArquivo = 'rel_erros.json';
    $arquivo = $localDir . $nmeArquivo;
    $cachetime = 24*60*60; // em segundos

    $infoFile = get_file_info($arquivo);
    if (!is_array($infoFile) || !count($infoFile) > 0) {
        $MsgModel = new MsgModel();
        $dados = $MsgModel->findAll();
        $data = Array(
            'lastupdate' => date('d/m/Y H:i:s'),
            'data' => $dados,
            'tipo' => 'base'
        );
        $dadosJson = json_encode($data);
        write_file($arquivo, $dadosJson);
    } else {
        $fileTime = $infoFile['date'];
        $tempo = time() - $cachetime;
        if ($tempo < $fileTime) {
            $data = json_decode(file_get_contents($arquivo), true);
            $data['tipo'] = 'cache';
        } else {
            $MsgModel = new MsgModel();
            $dados = $MsgModel->findAll();
            $data = Array(
                'lastupdate' => date('d/m/Y H:i:s'),
                'data' => $dados,
                'tipo' => 'new'
            );
            $dadosJson = json_encode($data);
            write_file($arquivo, $dadosJson);
        }
    }

    $erros = Array();
    foreach ($data['data'] as $key => $value) {
        $idd = (int) $value['id_msg'];
        $erros[$idd] = $value['message'];
    }    

    $idd = (is_numeric($idMSG)) ? (int) $idMSG : 0;
    $message = ($idd >= 0 && array_key_exists($idd, $erros)) ? $erros[$idd] : $erros[0];
    return $message;

}
