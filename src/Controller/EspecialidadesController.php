<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var EspecialidadeRepository
     */
    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $especialidadeRepository)
    {
        $this->entityManager = $entityManager;
        $this->especialidadeRepository = $especialidadeRepository;
    }

    /**
     * @Route("/especialidades", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function nova(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $dadosEmJson = json_decode($dadosRequest);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades", methods={"GET"})
     * @return Response
     */
    public function buscarTodas(): Response
    {
        $especialidades = $this->especialidadeRepository->findAll();

        return new JsonResponse($especialidades);
    }

    /**
     * @Route("/especialidades/{id}", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function buscarUma(int $id): Response
    {
        return new JsonResponse($this->especialidadeRepository->find($id));
    }

    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function atualiza(int $id, Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $dadosEmJson = json_decode($dadosRequest);

        $especialidade = $this->especialidadeRepository->find($id);

        if (is_null($especialidade)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $especialidade
            ->setDescricao($dadosEmJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"DELETE"})
     * @param int $id
     * @return Response
     * @throws ORMException
     */
    public function remove(int $id): Response
    {
        $especialidade = $this->entityManager->getReference(Especialidade::class, $id);

        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
