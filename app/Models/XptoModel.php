<?php

namespace App\Models;
use CodeIgniter\Model;
use Exception;

class XptoModel extends Model
{
    private $owner = 'xpto';

    public function getProdutoUK(int $id)
    {
        $tabela = sprintf('%s.%s', $this->owner, 'produtos');
        $criterio = ['id_produto' => $id];

        $builder = $this->db->table($tabela);
        $builder->where($criterio);
        $query = $builder->get();
        if (!$query) throw new Exception('Produto nao localizado!!');

        $result = $query->getResultArray();
        $query->freeResult();
        return (count($result) > 0) ? $result : [];
    }

    public function getProdutos()
    {
        $tabela = sprintf('%s.%s', $this->owner, 'produtos');
        $builder = $this->db->table($tabela);
        $query = $builder->get();
        $result = $query->getResultArray();
        $query->freeResult();
        return (count($result) > 0) ? $result : [];
    }

    public function putProdutos($data)
    {
        $tabela = sprintf('%s.%s', $this->owner, 'produtos');
        $builder = $this->db->table($tabela);
        $result = $builder->insert($data);
        if (!$result) throw new Exception('Falha na insercao de novos produtos!!');
        return $result;
    }

    public function updtProdutos($data, $produto)
    {
        $tabela = sprintf('%s.%s', $this->owner, 'produtos');
        $builder = $this->db->table($tabela);
        $criterio = ['id_produto' => (int) $produto];
        $result = $builder->update($data, $criterio);
        if (!$result) throw new Exception('Falha na atualizacao do produto!!');
        return $result;
    }

    public function putVendas($data)
    {
        $tabela = sprintf('%s.%s', $this->owner, 'venda_produtos');
        $builder = $this->db->table($tabela);
        $result = $builder->insertBatch($data);
        if (!$result) throw new Exception('Falha na Insercao de Novas Vendas!!');
        return $result;
    }

    public function putCompras($data)
    {
        $tabela = sprintf('%s.%s', $this->owner, 'compra_produtos');
        $builder = $this->db->table($tabela);
        $result = $builder->insertBatch($data);
        if (!$result) throw new Exception('Falha na Insercao de Novos Produtos!!');
        return $result;
    }

}