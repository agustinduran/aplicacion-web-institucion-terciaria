<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnunciosRepository")
 */
class Anuncios
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
    private $contenido;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_publi;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha_ini;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha_fin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getFechaPubli(): ?\DateTime
    {
        return $this->fecha_publi;
    }

    public function setFechaPubli(\DateTime $fecha_publi): self
    {
        $this->fecha_publi = $fecha_publi;

        return $this;
    }

    public function getFechaIni(): ?\DateTime
    {
        return $this->fecha_ini;
    }

    public function setFechaIni(\DateTime $fecha_ini): self
    {
        $this->fecha_ini = $fecha_ini;

        return $this;
    }

    public function getFechaFin(): ?\DateTime
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(\DateTime $fecha_fin): self
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }
}
