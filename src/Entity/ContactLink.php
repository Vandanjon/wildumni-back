<?php

namespace App\Entity;

use App\Repository\ContactLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContactLinkRepository::class)]
class ContactLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getUsers")]
    private ?string $github = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getUsers")]
    private ?string $gitlab = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getUsers")]
    private ?string $bitbucket = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getUsers")]
    private ?string $twitter = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getUsers")]
    private ?string $linkedin = null;

    #[ORM\ManyToOne(inversedBy: 'contactLink')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getGitlab(): ?string
    {
        return $this->gitlab;
    }

    public function setGitlab(?string $gitlab): self
    {
        $this->gitlab = $gitlab;

        return $this;
    }

    public function getBitbucket(): ?string
    {
        return $this->bitbucket;
    }

    public function setBitbucket(?string $bitbucket): self
    {
        $this->bitbucket = $bitbucket;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
