<?php
class PessoaFormView extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();
        $this->form = new BootstrapFormBuilder('form_pessoa');
        $this->form->setFormTitle('Cadastro de Pessoas');
        $tipo = new TCombo('tipo');
        $tipo->addItems(['F' => 'Física', 'J' => 'Jurídica']);
        $nome_completo = new TEntry('nome_completo');
        $razao_social = new TEntry('razao_social');
        $cpf = new TEntry('cpf');
        $cnpj = new TEntry('cnpj');
        $email = new TEntry('email');
        $telefone = new TEntry('telefone');
        $endereco_completo = new TText('endereco_completo');
        $tipo->setChangeAction(new TAction(['PessoaFormView', 'onChangeTipo'])); 
        $nome_completo->placeholder = 'Nome Completo';
        $razao_social->placeholder = 'Razão Social';
        $cpf->placeholder = 'CPF';
        $cpf->setMask('999.999.999-99'); 
        $cnpj->placeholder = 'CNPJ';
        $cnpj->setMask('99.999.999/9999-99'); 
        $email->placeholder = 'E-mail';
        $telefone->placeholder = 'Telefone';
        $telefone->setMask('(99) 99999-9999');
        $this->form->addFields([new TLabel('Tipo')], [$tipo]);
        $this->form->addFields([new TLabel('Nome Completo')], [$nome_completo]);
        $this->form->addFields([new TLabel('Razão Social')], [$razao_social]);
        $this->form->addFields([new TLabel('CPF')], [$cpf]);
        $this->form->addFields([new TLabel('CNPJ')], [$cnpj]);
        $this->form->addFields([new TLabel('E-mail')], [$email]);
        $this->form->addFields([new TLabel('Telefone')], [$telefone]);
        $this->form->addFields([new TLabel('Endereço Completo')], [$endereco_completo]);
        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save');
        parent::add($this->form);
        TQuickForm::hideField('form_pessoa', 'razao_social');
        TQuickForm::hideField('form_pessoa', 'cnpj');
    }

    public static function onEdit($param)
    {
        try {
            TTransaction::open('rio');
            $pessoa = new Pessoa($param['id']);
            $data = new stdClass;
            $data->id = $pessoa->id;
            $data->tipo = ($pessoa->tipo == 1) ? 'F' : 'J';
            $data->nome_completo = $pessoa->nome_completo;
            $data->razao_social = $pessoa->razao_social;
            $data->cpf = $pessoa->cpf;
            $data->cnpj = $pessoa->cnpj;
            $data->email = $pessoa->email;
            $data->telefone = $pessoa->telefone;
            $data->endereco_completo = $pessoa->endereco_completo;
            TForm::sendData('form_pessoa', $data);
            TTransaction::close();
            self::onChangeTipo(['tipo' => $data->tipo]);
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public static function onChangeTipo($param)
    {
        try {
            $obj = new stdClass;
            if ($param['tipo'] == 'F') {
                TQuickForm::showField('form_pessoa', 'cpf');
                TQuickForm::showField('form_pessoa', 'nome_completo');
                TQuickForm::hideField('form_pessoa', 'cnpj');
                TQuickForm::hideField('form_pessoa', 'razao_social');
            } else if ($param['tipo'] == 'J') {
                TQuickForm::showField('form_pessoa', 'cnpj');
                TQuickForm::showField('form_pessoa', 'razao_social');
                TQuickForm::hideField('form_pessoa', 'cpf');
                TQuickForm::hideField('form_pessoa', 'nome_completo');
            }
            TForm::sendData('form_pessoa', $obj);
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('rio');
            $this->form->validate();
            $data = $this->form->getData();
            if ($data->tipo == 'F') {
                if (Pessoa::where('cpf', '=', $data->cpf)->count() > 0) {
                    throw new Exception('CPF já cadastrado!');
                }
                $data->tipo = 1; 
                $data->razao_social = null; 
            } elseif ($data->tipo == 'J') {
                if (Pessoa::where('cnpj', '=', $data->cnpj)->count() > 0) {
                    throw new Exception('CNPJ já cadastrado!');
                }
                $data->tipo = 2;
                $data->nome_completo = null;
            }
            $pessoa = new Pessoa;
            $pessoa->fromArray((array) $data);
            if (empty($pessoa->data_cadastro)) {
                $pessoa->data_cadastro = date('Y-m-d H:i:s');
            }
            $pessoa->store();
            new TMessage('info', 'Registro salvo com sucesso');
            TTransaction::close();
            $this->form->setData(new stdClass);
            TScript::create("Template.gotoPage('index.php?class=PessoaList')");

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
?>
