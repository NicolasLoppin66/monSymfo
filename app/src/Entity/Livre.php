<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
#[UniqueEntity('titre', message: 'Ce livre existe déja !')]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(min: 4, minMessage: 'Vous devez mettre au minimum 4 caractère.', max: 200, maxMessage: 'Vous ete au-dela de la limite du titre.')]
    #[ORM\Column(length: 200)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateParution = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resume = null;

    #[Assert\Regex(pattern: '/\d/', match: false, message: "L'éditeur ne peut pas obtenir de nombre.")]
    #[ORM\Column(length: 150)]
    private string $editeur;

    #[ORM\ManyToMany(targetEntity: Auteur::class, inversedBy: 'livres')]
    #[UniqueEntity(fields: ['nom', 'prenom'])]
    private Collection $auteur;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    public function __construct()
    {
        $this->auteur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateParution(): ?\DateTimeInterface
    {
        return $this->dateParution;
    }

    public function setDateParution(\DateTimeInterface $dateParution): self
    {
        $this->dateParution = $dateParution;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * @return string
     */
    public function getEditeur(): string
    {
        return $this->editeur;
    }

    /**
     * @param string $editeur
     */
    public function setEditeur(string $editeur): self
    {
        $this->editeur = $editeur;

        return  $this;
    }

    public function dateformatParu(): string
    {
        $dateFormat = $this->getDateParution()->format('d/m/Y');
        return $dateFormat;
    }

    /**
     * @return Collection<int, Auteur>
     */
    public function getAuteur(): Collection
    {
        return $this->auteur;
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteur->contains($auteur)) {
            $this->auteur->add($auteur);
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        $this->auteur->removeElement($auteur);

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

}
