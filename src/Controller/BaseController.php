<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected ObjectRepository $repository;
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;
    /**
     * @var EntidadeFactory
     */
    protected EntidadeFactory $factory;
    /**
     * @var string
     */
    private string $entityName;
    /**
     * @var ExtratorDadosRequest
     */
    private ExtratorDadosRequest $extrator;

    /**
     * BaseController constructor.
     * @param string $entityName
     * @param ObjectRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param EntidadeFactory $factory
     * @param ExtratorDadosRequest $extrator
     */
    public function __construct(
        string $entityName,
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactory $factory,
        ExtratorDadosRequest $extrator
    )
    {
        $this->entityName = $entityName;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extrator = $extrator;
    }

    public function buscarTodos(Request $request): Response
    {
        $ordenacao = $this->extrator->buscaDadosOrdenacao($request);
        $filtro = $this->extrator->buscaDadosFiltro($request);
        [$paginaAtual, $itensPorPagina] = $this->extrator->buscaDadosPaginacao($request);

        $entityList = $this->repository->findBy($filtro, $ordenacao, $itensPorPagina, ($paginaAtual - 1) * $itensPorPagina);

        return new JsonResponse($entityList);
    }

    public function buscarUm(int $id): Response
    {
        return new JsonResponse($this->repository->find($id));
    }

    public function novo(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $entidade = $this->factory->criarEntidade($dadosRequest);

        $this->entityManager->persist($entidade);
        $this->entityManager->flush();

        return new JsonResponse($entidade);
    }

    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $entidadeEnviada = $this->factory->criarEntidade($corpoRequisicao);

        $entidadeExistente = $this->repository->find($id);

        if (is_null($entidadeExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $this->atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);

        $this->entityManager->flush();

        return new JsonResponse($entidadeExistente);
    }

    protected abstract function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);

    public function remove(int $id): Response
    {
        $entity = $this->entityManager->getReference($this->entityName, $id);

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}