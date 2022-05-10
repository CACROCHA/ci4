<?php

namespace App\Controllers;

use App\Models\XptoModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;
use ReflectionException;

class ApiV1 extends BaseController
{
    private $apiversion = "1;2022-05-05";
    private $funcoes = ['produtos', 'vendas', 'compras'];
    private $tpMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    public function index()
    {
        return view('welcome_message');
    }

    public function fnc($funcao)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $responseCode = ResponseInterface::HTTP_BAD_REQUEST;
        
        helper('msg');
        helper('api');

        try {
            $output = $this->restOutput;
            $output['apiVersion']    = $this->apiversion;
            $output['transactionId'] = transactionID();
            $output['method'] = $method;

            if (in_array($method, $this->tpMethods)) {
                switch ($method) {
                    case 'GET':
                        $input = $this->getRequestInputGET($this->request);
                        if (!is_null($input)) {
                            switch ($funcao) {
                                case 'produtos':
                                    $rules  = ['id' => 'required'];
                                    break;
                            }
                            if (!$this->validateRequest($input, $rules)) throw new Exception(getMSG(21), 21);
                        }
                        break;

                    case 'PUT':
                        $input = $this->getRequestInputPOST($this->request);
                        switch ($funcao) {
                            case 'produtos':
                                $rules  = [
                                    'descricao' => 'required',
                                    'qtde' => 'required'
                                ];
                                break;
                            case 'vendas':
                            case 'compras':
                                $rules  = [
                                    'descricao' => 'required',
                                    'prods' => 'required'
                                ];
                                break;
                        }
                        if (!$this->validateRequest($input, $rules)) throw new Exception(getMSG(20), 20);
                        break;

                    case 'POST':
                        $input = $this->getRequestInputPOST($this->request);
                        switch ($funcao) {
                            case 'produtos':
                                $rules  = [
                                    'produto' => 'required',
                                    'qtde' => 'required'
                                ];
                                $method = 'UPDT';
                                break;
                        }
                        if (!$this->validateRequest($input, $rules)) throw new Exception(getMSG(20), 20);
                        break;
                }

            } else throw new Exception(getMSG(28));
            
            $disponiveis = $this->funcoes;
        
        	// if (! Services::validarBase64($funcao)) throw new Exception(getMSG(21));
        	// $strResult = base64_decode($funcao, true);
        	if (in_array($funcao, $disponiveis)) {
        		$func = strtolower($method) . ucfirst(strtolower($funcao));
        		$result = $func($input);
                
                // $output['input'] = $input;
                $output['data'] = $result;
                return $this->getResponse($output);

            } else throw new Exception(getMSG(13));

        } catch (Exception $e) {
            $message = $e->getMessage();
            // $detailedMessage = $message;
            $errorCode  = $e->getCode();
            $prefixo    = 'API';
            $posprefixo = 'TESTE';

            $link = Array(
                'rel' => "related",
                'href' => "https://localhost:9123/docs"
            );

            $apiErrorCode = (!$errorCode || $errorCode === 0) ? $responseCode : $errorCode;
            $apiErrorText = sprintf('%s-%s-%s', $prefixo, $posprefixo, $apiErrorCode);

            $error = Array(
                'httpCode' => $responseCode,
                'errorCode' => $apiErrorText,
                'message' => $message,
            //     'detailedMessage' => $detailedMessage,
                'link' => $link
            );

            $output['error'] = $error;
            unset($output['data']);
        	return $this->getResponse($output, $responseCode);
        }
    }

}