<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class EspecialidadesController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $especialidadeRepository,
        EspecialidadeFactory $factory,
        ExtratorDadosRequest $extrator)
    {
        parent::__construct(Especialidade::class, $especialidadeRepository, $entityManager, $factory, $extrator);

        $this->factory = $factory;
    }

    /**
     * @param int $id
     * @param $entidade
     * @return Especialidade
     */
    public function atualizaEntidadeExistente(int $id, $entidade)
    {
        /** @var Especialidade $entidadeExistente */
        $entidadeExistente = $this->repository->find($id);
        if (is_null($entidadeExistente)) {
            throw new InvalidArgumentException();
        }
        $entidadeExistente->setDescricao($entidade->getDescricao());

        return $entidadeExistente;
    }

}
