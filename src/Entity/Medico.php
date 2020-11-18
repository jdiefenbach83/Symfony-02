<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Medico implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;
    /**
     * @ORM\Column(type="integer")
     */
    private int $crm;
    /**
     * @ORM\Column(type="string")
     */
    private string $nome;

    /**
     * @ORM\ManyToOne(targetEntity=Especialidade::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Especialidade $especialidade;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCrm(): int
    {
        return $this->crm;
    }

    /**
     * @param int $crm
     * @return Medico
     */
    public function setCrm(int $crm): Medico
    {
        $this->crm = $crm;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Medico
     */
    public function setNome(string $nome): Medico
    {
        $this->nome = $nome;
        return $this;
    }

    public function getEspecialidade(): ?Especialidade
    {
        return $this->especialidade;
    }

    public function setEspecialidade(?Especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'crm' => $this->crm,
            'nome' => $this->nome,
            'especialidadeId' => $this->getEspecialidade()->getId()
        ];
    }
}