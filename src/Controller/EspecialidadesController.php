<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadesController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $especialidadeRepository,
        EspecialidadeFactory $factory)
    {
        parent::__construct(Especialidade::class, $especialidadeRepository, $entityManager, $factory);

        $this->factory = $factory;
    }

    /**
     * @param Especialidade $entidadeExistente
     * @param Especialidade $entidadeEnviada
     */
    protected function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente
            ->setDescricao($entidadeEnviada->getDescricao());
    }
}
