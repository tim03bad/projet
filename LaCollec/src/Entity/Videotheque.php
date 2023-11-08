<?php

namespace App\Entity;

use App\Repository\VideothequeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: VideothequeRepository::class)]
#[ApiResource]
class Videotheque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'videotheque', targetEntity: Video::class, cascade: ['persist'])]
    private Collection $contenu;

    #[ORM\ManyToOne(inversedBy: 'Videotheque')]
    private ?Member $member = null;

    public function __construct()
    {
        $this->contenu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getContenu(): Collection
    {
        return $this->contenu;
    }

    public function addContenu(Video $contenu): static
    {
        if (!$this->contenu->contains($contenu)) {
            $this->contenu->add($contenu);
            $contenu->setVideotheque($this);
        }

        return $this;
    }

    public function removeContenu(Video $contenu): static
    {
        if ($this->contenu->removeElement($contenu)) {
            // set the owning side to null (unless already changed)
            if ($contenu->getVideotheque() === $this) {
                $contenu->setVideotheque(null);
            }
        }

        return $this;
    }
    
    public function __toString() : string
    {
        return $this->getDescription();
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        $this->member = $member;

        return $this;
    }
}
