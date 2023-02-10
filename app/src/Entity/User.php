<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, \Serializable, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $username = null;

    #[ORM\Column(length: 250)]
    private ?string $password = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(length: 150)]
    private ?string $prenom = null;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
	/**
	 * Returns the roles granted to the user.
	 *
	 * public function getRoles()
	 * {
	 * return ['ROLE_USER'];
	 * }
	 *
	 * Alternatively, the roles might be stored in a ``roles`` property,
	 * and populated in any number of different ways when the user object
	 * is created.
	 * @return array<string>
	 */
	public function getRoles(): array 
    {
        return ['ROLE_ADMIN'];
	}
	
	/**
	 * Removes sensitive data from the user.
	 *
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 * @return mixed
	 */
	public function eraseCredentials() 
    {

	}
	
	/**
	 * Returns the identifier for this user (e.g. username or email address).
	 * @return string
	 */
	public function getUserIdentifier(): string 
    {
        return $this->username;
	}
	
	/**
	 * String representation of object
	 * Should return the string representation of the object.
	 * @return null|string Returns the string representation of the object or `null`
	 */
	public function serialize() 
    {
        return serialize([
            $this->id,
            $this->password,
            $this->username,
        ]);
	}
	
	/**
	 * Constructs the object
	 * Called during unserialization of the object.
	 *
	 * @param string $data The string representation of the object.
	 * @return void
	 */
	public function unserialize($serialized) 
    {
        list($this->id, $this->username, $this->password) = unserialize($serialized, ["allowed_classes" => false]);
	}

    /**
     * @Route("Route", name="RouteName")
     */
    public function getSalt(): ?string
    {
        return null;
    }
}
