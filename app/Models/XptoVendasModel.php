<?php

namespace App\Models;
use CodeIgniter\Model;
use Exception;

class XptoVendasModel extends Model
{
    protected $table = 'vendas';
    protected $primaryKey = 'id_venda';
    protected $allowedFields = ['id_venda', 'descricao', 'dh_venda'];
}