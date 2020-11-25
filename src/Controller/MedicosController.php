<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\ExtratorDadosRequest;
use App\Helper\MedicoFactory;
use App\Repository\MedicosRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param Medico $entidadeExistente
     * @param Medico $entidadeEnviada
     */
    protected function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente
            ->setCrm($entidadeEnviada->getCrm())
            ->setNome($entidadeEnviada->getNome())
            ->setEspecialidade($entidadeEnviada->getEspecialidade());
    }

    public function buscarPorEspecialidade(int $especialidadeId): Response
    {
        $medicos = $this->repository->findBy(['especialidade' => $especialidadeId]);

        return new JsonResponse($medicos);
    }
}
