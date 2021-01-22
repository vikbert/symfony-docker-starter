<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private string $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $token;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function login(): void
    {
        $this->token = Uuid::uuid4()->toString();
    }
    
    public function logout(): void
    {
        $this->token = null;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function setRoles(array $roles): array
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function setUsername(string $username): self
    {
        $this->email = $username;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): bool
    {
        return true;
    }
}
