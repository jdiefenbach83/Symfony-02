<?php

namespace App\Controller;

use App\Entity\Medico;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $dadoEmJson = json_decode($corpoRequisicao);

        $medico = new Medico();
        $medico->crm = $dadoEmJson->crm;
        $medico->nome = $dadoEmJson->nome;

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     * @return Response
     */
    public function buscarTodos(): Response
    {
        $medicoRepository = $this->getDoctrine()->getRepository(Medico::class);
        $medicos = $medicoRepository->findAll();

        return new JsonResponse($medicos);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function buscarUm(Request $request): Response
    {
        $id = $request->get('id');

        $medicoRepository = $this->getDoctrine()->getRepository(Medico::class);
        $medico = $medicoRepository->find($id);

        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoRetorno);
    }

}