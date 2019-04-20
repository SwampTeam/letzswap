<?php

namespace App\Entity;

use App\Entity\Status;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="4", max="180")
     */
    private $username;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="8", max="255")
     * @Assert\Regex(
     * pattern= "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,255}$/",
     * message="Password need 1 upper case, 1 lower case, 1 number"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $emailConfirmed;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\NotBlank()
     * @Assert\IsTrue()
     */
    private $tosAccepted;

    /**
     * @ORM\Column(type="guid", nullable=true, unique=true)
     */
    private $activationToken;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    private $items;

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     * @return User
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="UserStatus", mappedBy="users", cascade={"remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="user_status")
     */
    private $statuses;

    /**
     * @return mixed
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @param mixed $statuses
     * @return User
     */
    public function setStatuses($statuses)
    {
        $this->statuses = $statuses;
        return $this;
    }

    /**
     * @param $status
     * @return User
     */
    public function addStatus(Status $status)
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses[] = $status;
        }
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getTosAccepted(): ?bool
    {
        return $this->tosAccepted;
    }

    public function setTosAccepted(?bool $tosAccepted): self
    {
        $this->tosAccepted = $tosAccepted;

        return $this;
    }

    public function getEmailConfirmed(): ?bool
    {
        return $this->emailConfirmed;
    }

    public function setEmailConfirmed(?bool $emailConfirmed): self
    {
        $this->emailConfirmed = $emailConfirmed;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function setActivationToken(?string $activationToken): self
    {
        $this->activationToken = $activationToken;

        return $this;
    }

}
