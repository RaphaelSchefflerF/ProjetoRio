<?php

class TEntryExtended extends TEntry
{
    private $validators = [];

    /**
     * Remove todas as validações do campo
     */
    public function clearValidators()
    {
        $this->validators = [];
    }

    /**
     * Adiciona uma validação ao campo (sobrescrevendo o método existente)
     */
    public function addValidation($label, TValidator $validator)
    {
        parent::addValidation($label, $validator);
        $this->validators[] = $validator;
    }
}
?>
