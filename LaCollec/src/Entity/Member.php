<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Videotheque::class, cascade: ['persist'])]
    private Collection $Videotheque;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Selection::class, cascade: ['persist'])]
    private Collection $selection;

    #[ORM\OneToOne(inversedBy: 'member', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $User = null;

    public function __construct()
    {
        $this->Videotheque = new ArrayCollection();
        $this->selection = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
    
    public function __toString() : string
    {
        return $this->getNom();
    }

    /**
     * @return Collection<int, Videotheque>
     */
    public function getVideotheque(): Collection
    {
        return $this->Videotheque;
    }

    public function addVideotheque(Videotheque $videotheque): static
    {
        if (!$this->Videotheque->contains($videotheque)) {
            $this->Videotheque->add($videotheque);
            $videotheque->setMember($this);
        }

        return $this;
    }

    public function removeVideotheque(Videotheque $videotheque): static
    {
        if ($this->Videotheque->removeElement($videotheque)) {
            // set the owning side to null (unless already changed)
            if ($videotheque->getMember() === $this) {
                $videotheque->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Selection>
     */
    public function getSelection(): Collection
    {
        return $this->selection;
    }

    public function addSelection(Selection $selection): static
    {
        if (!$this->selection->contains($selection)) {
            $this->selection->add($selection);
            $selection->setMember($this);
        }

        return $this;
    }

    public function removeSelection(Selection $selection): static
    {
        if ($this->selection->removeElement($selection)) {
            // set the owning side to null (unless already changed)
            if ($selection->getMember() === $this) {
                $selection->setMember(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
