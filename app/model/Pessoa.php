<?php
use Adianti\Database\TRecord;

class Pessoa extends TRecord
{
    const TABLENAME  = 'pessoa';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; 

    public function __construct($id = null)
    {
        parent::__construct($id);
        parent::addAttribute('tipo');
        parent::addAttribute('nome_completo');
        parent::addAttribute('cpf');
        parent::addAttribute('cnpj');
        parent::addAttribute('email');
        parent::addAttribute('telefone');
        parent::addAttribute('endereco_completo');
        parent::addAttribute('data_cadastro');
    }
}
?>
