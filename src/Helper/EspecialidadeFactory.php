<?php

namespace App\Helper;

use App\Entity\Especialidade;

class EspecialidadeFactory implements EntidadeFactory
{
    public function criarEntidade(string $json): Especialidade
    {
        $dadosEmJson = json_decode($json);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        return $especialidade;
    }
}