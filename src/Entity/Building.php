<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BuildingRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
#[ApiResource(operations: [
    new GetCollection(),
    new Get()
])]
class Building
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id = "";

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Room::class, mappedBy: 'building')]
    private $rooms;


    public function __construct()
    {
        $this->rooms = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRooms(): Collection
    {
        return $this->rooms;
    }
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }
    public function setRooms($rooms): self
    {
        $this->rooms = $rooms;
        return $this;
    }

    public function addRoom($room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
        }

        return $this;
    }

}
