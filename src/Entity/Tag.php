<?php

namespace App\Entity;

use App\Entity\Bulletin;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Bulletin", mappedBy="tags", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $bulletins; //* Attributs relié à Bulletin.php avec le inversedBy

    public function __construct()
    {
        $this->bulletins = new ArrayCollection();
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Bulletin[]
     */
    public function getBulletins(): Collection
    {
        return $this->bulletins;
    }

    public function addBulletin(Bulletin $bulletin): self
    {
        if (!$this->bulletins->contains($bulletin)) {
            $this->bulletins[] = $bulletin;
            $bulletin->addTag($this);
        }

        return $this;
    }

    public function removeBulletin(Bulletin $bulletin): self
    {
        if ($this->bulletins->removeElement($bulletin)) {
            $bulletin->removeTag($this);
        }

        return $this;
    }
}
