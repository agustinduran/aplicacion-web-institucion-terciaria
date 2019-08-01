<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlumnoRepository")
 */
class Alumno
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cambiopass;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;


    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCambiopass(): ?bool
    {
        return $this->cambiopass;
    }

    public function setCambiopass(bool $cambiopass): self
    {
        $this->cambiopass = $cambiopass;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
