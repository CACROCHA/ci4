<?php

namespace App\Models;
use CodeIgniter\Model;
use Exception;

class XptoComprasModel extends Model
{
    protected $table = 'compras';
    protected $primaryKey = 'id_compra';
    protected $allowedFields = ['id_compra', 'descricao', 'dh_compra'];
}