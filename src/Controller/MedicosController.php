<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var MedicoFactory
     */
    private MedicoFactory $medicoFactory;
    /**
     * @var MedicosRepository
     */
    private MedicosRepository $medicosRepository;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $medicoFactory, MedicosRepository $medicosRepository)
    {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
        $this->medicosRepository = $medicosRepository;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

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
        $medicos = $this->medicosRepository->findAll();

        return new JsonResponse($medicos);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function buscarUm(int $id): Response
    {
        $medico = $this->buscaMedico($id);

        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $codigoRetorno);
    }


    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medicoEnviado = $this->medicoFactory->criarMedico($corpoRequisicao);

        $medicoExistente = $this->buscaMedico($id);

        if (is_null($medicoExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medicoExistente
            ->setCrm($medicoEnviado->getCrm())
            ->setNome($medicoEnviado->getNome());

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }

    /**
     * @param int $id
     * @return object|null
     */
    private function buscaMedico(int $id)
    {
        return $this->medicosRepository->find($id);
    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     * @param int $id
     * @return Response
     * @throws ORMException
     */
    public function remove(int $id): Response
    {
        $medico = $this->entityManager->getReference(Medico::class, $id);

        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"})
     * @param int $especialidadeId
     * @return Response
     */
    public function buscarPorEspecialidade(int $especialidadeId): Response
    {
        $medicos = $this->medicosRepository->findBy(['especialidade' => $especialidadeId]);

        return new JsonResponse($medicos);
    }
}
