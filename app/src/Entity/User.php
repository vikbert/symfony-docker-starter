<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
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

    private function __construct(string $email)
    {
        $this->email = $email;
        $this->id = Uuid::uuid4()->toString();
    }

    public static function create(string $email): self
    {
        return new self($email);
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
}
