<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFactory
{
    private bool $sucesso;
    private $conteudo;
    private int $status;
    private ?int $paginaAtual;
    private ?int $itensPorPagina;

    public function __construct(
        bool $sucesso,
        $conteudo,
        int $status,
        int $paginaAtual = null,
        int $itensPorPagina = null)
    {
        $this->sucesso = $sucesso;
        $this->conteudo = $conteudo;
        $this->status = $status;
        $this->paginaAtual = $paginaAtual;
        $this->itensPorPagina = $itensPorPagina;
    }

    public function getResponse(): JsonResponse
    {
        $conteudo = [
            'sucesso' => $this->sucesso,
            'pagina_atual' => $this->paginaAtual,
            'itens_por_pagina' => $this->itensPorPagina,
            'conteudo' => $this->conteudo
        ];

        if (is_null($this->paginaAtual)) {
            unset($conteudo['pagina_atual']);
            unset($conteudo['itens_por_pagina']);
        }

        return new JsonResponse($conteudo, $this->status);
    }

}