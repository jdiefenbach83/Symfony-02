<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\ExtratorDadosRequest;
use App\Helper\MedicoFactory;
use App\Repository\MedicosRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MedicosController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        MedicosRepository $medicosRepository,
        MedicoFactory $factory,
        ExtratorDadosRequest $extrator)
    {
        parent::__construct(Medico::class, $medicosRepository, $entityManager, $factory, $extrator);

        $this->factory = $factory;
    }

    /**
     * @param int $id
     * @param $entidade
     * @return Medico
     */
    public function atualizaEntidadeExistente(int $id, $entidade)
    {
        /** @var Medico $entidadeExistente */
        $entidadeExistente = $this->repository->find($id);
        if (is_null($entidadeExistente)) {
            throw new InvalidArgumentException();
        }
        $entidadeExistente
            ->setCrm($entidade->getCrm())
            ->setNome($entidade->getNome());

        return $entidadeExistente;
    }


    public function buscarPorEspecialidade(int $especialidadeId): Response
    {
        $medicos = $this->repository->findBy(['especialidade' => $especialidadeId]);

        return new JsonResponse($medicos);
    }
}
