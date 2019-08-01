<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=127, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=127, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $role;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return [$this->role];
    }    

    public function getSalt(){}

    public function eraseCredentials(){}

    public function serialize(){
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->role
        ]);
    }

    public function unserialize($string){
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->role
        ) = unserialize($string, ['allowed_classes' => false]);
    }    
}
