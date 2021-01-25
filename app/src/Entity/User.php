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
    private string $username;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password;
    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $authToken;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $accessToken;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function login(): void
    {
        $this->authToken = Uuid::uuid4()->toString();
        $this->roles = ['ROLE_USER'];
    }

    public function logout(): void
    {
        $this->authToken = null;
        $this->roles = null;
        $this->accessToken = null;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    public function getRoles(): array
    {
        return $this->roles ?? [];
    }

    public function setRoles(array $roles): self
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

    public function eraseCredentials(): bool
    {
        return true;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
