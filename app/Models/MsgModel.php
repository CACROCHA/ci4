<?php 

namespace App\Models;
use CodeIgniter\Model;
use Exception;

class MsgModel extends Model
{
    protected $table = 'xpto.rel_error_msg';
    protected $primaryKey = 'lang, id_msg';

    protected $allowedFields = ['id_msg', 'message'];
                                      
    public function getMessageError(int $id)
    {
        $msg = $this
            ->asArray()
            ->where(['lang' => 'pt-BR', 'id_msg' => $id])
            ->first();

        if (!$id) throw new Exception('Codigo de erro nao informado!!');

        return $msg;
    }

}