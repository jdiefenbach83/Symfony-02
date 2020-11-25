<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtratorDadosRequest
{
    private function buscaDadosRequest(Request $request)
    {
        $ordenacao = $request->query->get('sort');
        $filtro = $request->query->all();
        unset($filtro['sort']);
        $paginaAtual = $request->query->get('page') ?? 1;
        unset($filtro['page']);
        $itensPorPagina = $request->query->get('itensPorPagina') ?? 5;
        unset($filtro['itensPorPagina']);

        return [
            $ordenacao,
            $filtro,
            $paginaAtual,
            $itensPorPagina
        ];
    }

    public function buscaDadosOrdenacao(Request $request)
    {
        [$ordenacao,] = $this->buscaDadosRequest($request);

        return $ordenacao;
    }

    public function buscaDadosFiltro(Request $request)
    {
        [, $filtro] = $this->buscaDadosRequest($request);

        return $filtro;
    }

    public function buscaDadosPaginacao(Request $request)
    {
        [, , $paginaAtual, $itensPorPagina] = $this->buscaDadosRequest($request);

        return [$paginaAtual, $itensPorPagina];
    }
}