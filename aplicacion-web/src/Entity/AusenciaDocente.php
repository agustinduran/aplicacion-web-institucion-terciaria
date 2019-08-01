<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AusenciaDocenteRepository")
 */
class AusenciaDocente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $docente;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaIni;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaFin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocente(): ?string
    {
        return $this->docente;
    }

    public function setDocente(string $docente): self
    {
        $this->docente = $docente;

        return $this;
    }

    public function getFechaIni(): ?\DateTimeInterface
    {
        return $this->fechaIni;
    }

    public function setFechaIni(\DateTimeInterface $fechaIni): self
    {
        $this->fechaIni = $fechaIni;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTimeInterface $fechaFin): self
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }
}
