<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("getUsers")]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups("getUsers")]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups("getUsers")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups("getUsers")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups("getUsers")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getUsers")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getUsers")]
    private ?string $userName = null;

    #[ORM\ManyToMany(targetEntity: Session::class, inversedBy: 'users')]
    #[Groups("getUsers")]
    private Collection $session;

    #[ORM\ManyToMany(targetEntity: Language::class, inversedBy: 'users')]
    #[Groups("getUsers")]
    private Collection $language;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ContactLink::class, orphanRemoval: true)]
    #[Groups("getUsers")]
    private Collection $contactLink;

    public function __construct()
    {
        $this->session = new ArrayCollection();
        $this->language = new ArrayCollection();
        $this->contactLink = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSession(): Collection
    {
        return $this->session;
    }

    public function addSession(Session $session): self
    {
        if (!$this->session->contains($session)) {
            $this->session->add($session);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        $this->session->removeElement($session);

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguage(): Collection
    {
        return $this->language;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->language->contains($language)) {
            $this->language->add($language);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        $this->language->removeElement($language);

        return $this;
    }

    /**
     * @return Collection<int, ContactLink>
     */
    public function getContactLink(): Collection
    {
        return $this->contactLink;
    }

    public function addContactLink(ContactLink $contactLink): self
    {
        if (!$this->contactLink->contains($contactLink)) {
            $this->contactLink->add($contactLink);
            $contactLink->setUser($this);
        }

        return $this;
    }

    public function removeContactLink(ContactLink $contactLink): self
    {
        if ($this->contactLink->removeElement($contactLink)) {
            // set the owning side to null (unless already changed)
            if ($contactLink->getUser() === $this) {
                $contactLink->setUser(null);
            }
        }

        return $this;
    }
}
