<?php
class PessoaList extends TPage
{
    private $datagridFisica;
    private $datagridJuridica;

    public function __construct()
    {
        parent::__construct();

        // Criação do DataGrid para Pessoas Físicas
        $this->datagridFisica = new TDataGrid;

        // Criação das colunas para Pessoas Físicas
        $col_id_fisica = new TDataGridColumn('id', 'ID', 'center', '10%');
        $col_nome_fisica = new TDataGridColumn('nome_completo', 'Nome', 'left', '30%');
        $col_cpf_fisica = new TDataGridColumn('cpf', 'CPF', 'left', '20%');
        $col_email_fisica = new TDataGridColumn('email', 'E-mail', 'left', '20%');
        $col_telefone_fisica = new TDataGridColumn('telefone', 'Telefone', 'left', '20%');

        // Adicionando colunas ao DataGrid de Pessoas Físicas
        $this->datagridFisica->addColumn($col_id_fisica);
        $this->datagridFisica->addColumn($col_nome_fisica);
        $this->datagridFisica->addColumn($col_cpf_fisica);
        $this->datagridFisica->addColumn($col_email_fisica);
        $this->datagridFisica->addColumn($col_telefone_fisica);

        // Ações de Edição e Exclusão para Pessoas Físicas
        $action_edit_fisica = new TDataGridAction(['PessoaEdit', 'onEdit'], ['id' => '{id}']);
        $action_delete_fisica = new TDataGridAction([$this, 'onDelete'], ['id' => '{id}']);
        $this->datagridFisica->addAction($action_edit_fisica, 'Editar', 'fa:edit blue');
        $this->datagridFisica->addAction($action_delete_fisica, 'Excluir', 'fa:trash red');

        // Criação do modelo de DataGrid e adicionando ao painel para Pessoas Físicas
        $this->datagridFisica->createModel();
        $panelFisica = new TPanelGroup('Listagem de Pessoas Físicas');
        $panelFisica->add($this->datagridFisica);

        // Criação do DataGrid para Pessoas Jurídicas
        $this->datagridJuridica = new TDataGrid;

        // Criação das colunas para Pessoas Jurídicas
        $col_id_juridica = new TDataGridColumn('id', 'ID', 'center', '10%');
        $col_razao_social_juridica = new TDataGridColumn('razao_social', 'Razão Social', 'left', '30%');
        $col_cnpj_juridica = new TDataGridColumn('cnpj', 'CNPJ', 'left', '20%');
        $col_email_juridica = new TDataGridColumn('email', 'E-mail', 'left', '20%');
        $col_telefone_juridica = new TDataGridColumn('telefone', 'Telefone', 'left', '20%');

        // Adicionando colunas ao DataGrid de Pessoas Jurídicas
        $this->datagridJuridica->addColumn($col_id_juridica);
        $this->datagridJuridica->addColumn($col_razao_social_juridica);
        $this->datagridJuridica->addColumn($col_cnpj_juridica);
        $this->datagridJuridica->addColumn($col_email_juridica);
        $this->datagridJuridica->addColumn($col_telefone_juridica);

        // Ações de Edição e Exclusão para Pessoas Jurídicas
        $action_edit_juridica = new TDataGridAction(['PessoaEdit', 'onEdit'], ['id' => '{id}']);
        $action_delete_juridica = new TDataGridAction([$this, 'onDelete'], ['id' => '{id}']);
        $this->datagridJuridica->addAction($action_edit_juridica, 'Editar', 'fa:edit blue');
        $this->datagridJuridica->addAction($action_delete_juridica, 'Excluir', 'fa:trash red');

        // Criação do modelo de DataGrid e adicionando ao painel para Pessoas Jurídicas
        $this->datagridJuridica->createModel();
        $panelJuridica = new TPanelGroup('Listagem de Pessoas Jurídicas');
        $panelJuridica->add($this->datagridJuridica);

        // Adicionando os painéis à página
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($panelFisica);
        $vbox->add($panelJuridica);

        parent::add($vbox);
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('rio');

            // Obtém os dados do formulário
            $data = $this->form->getData();

            // Atualiza a pessoa no banco de dados
            $pessoa = new Pessoa($data->id);
            $pessoa->fromArray((array) $data);
            $pessoa->store();

            TTransaction::close();
            new TMessage('info', 'Registro atualizado com sucesso');

            // Recarrega a listagem
            $this->onReload();
            $this->window->hide();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onReload()
    {
        try {
            TTransaction::open('rio');

            // Obtém todas as pessoas físicas
            $repository = new TRepository('Pessoa');
            $criteriaFisica = new TCriteria;
            $criteriaFisica->add(new TFilter('tipo', '=', 'Físico')); // Tipo 'Físico': Pessoa Física
            $criteriaFisica->setProperty('order', 'id');
            $pessoasFisicas = $repository->load($criteriaFisica);

            $this->datagridFisica->clear();
            if ($pessoasFisicas) {
                foreach ($pessoasFisicas as $pessoa) {
                    $this->datagridFisica->addItem($pessoa);
                }
            }

            // Obtém todas as pessoas jurídicas
            $criteriaJuridica = new TCriteria;
            $criteriaJuridica->add(new TFilter('tipo', '=', 'Jurídico')); // Tipo 'Jurídico': Pessoa Jurídica
            $criteriaJuridica->setProperty('order', 'id');
            $pessoasJuridicas = $repository->load($criteriaJuridica);

            $this->datagridJuridica->clear();
            if ($pessoasJuridicas) {
                foreach ($pessoasJuridicas as $pessoa) {
                    $this->datagridJuridica->addItem($pessoa);
                }
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function onDelete($param)
    {
        $action = new TAction([$this, 'deleteRecord']);
        $action->setParameter('id', $param['id']);

        new TQuestion('Você tem certeza que deseja excluir o registro?', $action);
    }

    public function deleteRecord($param)
    {
        try {
            TTransaction::open('rio');

            // Deleta a pessoa pelo ID
            $pessoa = new Pessoa($param['id']);
            $pessoa->delete();

            TTransaction::close();
            new TMessage('info', 'Registro excluído com sucesso');

            // Recarrega a listagem
            $this->onReload();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function show()
    {
        $this->onReload();
        parent::show();
    }
}
?>
