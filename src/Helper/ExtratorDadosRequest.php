<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtratorDadosRequest
{
    public function buscaDadosOrdenacao(Request $request)
    {
        [$ordenacao,] = $this->buscaDadosRequest($request);

        return $ordenacao;
    }

    private function buscaDadosRequest(Request $request)
    {
        $ordenacao = $request->query->get('sort');
        $filtro = $request->query->all();
        unset($filtro['sort']);

        return [
            $ordenacao,
            $filtro
        ];
    }

    public function buscaDadosFiltro(Request $request)
    {
        [, $filtro] = $this->buscaDadosRequest($request);

        return $filtro;
    }
}