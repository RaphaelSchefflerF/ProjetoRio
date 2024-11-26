<?php
class PessoaEdit extends TPage
{
    private $form;
    public function __construct()
    {
        parent::__construct();
        $this->form = new BootstrapFormBuilder('form_edit_pessoa');
        $this->form->setFormTitle('Editar Pessoa');
        $id_field = new TEntry('id');
        $id_field->setEditable(false);
        $this->form->addFields([new TLabel('ID')], [$id_field]);
        $this->form->addFields([new TLabel('Nome Completo')], [new TEntry('nome_completo')]);
        $this->form->addFields([new TLabel('CPF')], [new TEntry('cpf')]);
        $this->form->addFields([new TLabel('Razão Social')], [new TEntry('razao_social')]);
        $this->form->addFields([new TLabel('CNPJ')], [new TEntry('cnpj')]);
        $this->form->addFields([new TLabel('E-mail')], [new TEntry('email')]);
        $this->form->addFields([new TLabel('Telefone')], [new TEntry('telefone')]);
        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save green');
        parent::add($this->form);
    }

    public function onEdit($param)
    {
        try {
            TTransaction::open('rio');
            $pessoa = new Pessoa($param['id']);
            $this->form->setData($pessoa);
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('rio');
            $data = $this->form->getData();
            $pessoa = new Pessoa($data->id);
            $pessoa->fromArray((array) $data);
            $pessoa->store();
            TTransaction::close();
            new TMessage('info', 'Registro atualizado com sucesso');
            TScript::create("Template.gotoPage('index.php?class=PessoaList')");
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
?>
