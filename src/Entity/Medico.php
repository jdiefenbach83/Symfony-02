<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Medico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public ?int $id;
    /**
     * @ORM\Column(type="integer")
     */
    public int $crm;
    /**
     * @ORM\Column(type="string")
     */
    public string $nome;
}