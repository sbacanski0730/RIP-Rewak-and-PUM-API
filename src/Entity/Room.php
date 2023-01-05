<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use App\Entity\Building;
use App\Entity\Event;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ApiResource(operations: [
        new GetCollection(),
        new Get()
    ],
    denormalizationContext: ['groups' => ['write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['building.id' => 'exact'])]
#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $locationName = null;

    #[Groups('write')]
    #[ORM\ManyToOne(targetEntity: Building::class, inversedBy: 'rooms')]
    private $building;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'room', fetch: "EAGER")]
    private $events;

    public function __construct() {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBuilding(): Building
    {
        return $this->building;
    }

    public function getLocationName(): ?string
    {
        return $this->locationName;
    }

    public function getEvents(): Collection
    {
        return $this->events;
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

    public function setBuilding($building): self
    {
        $this->building = $building;

        preg_match('/[bB]udynek\s(?<buildingSymbol>\w{1,2})$/', $building->getId(), $matches);
        if (array_key_exists('buildingSymbol', $matches)) {
            $this->locationName = $matches['buildingSymbol'] . $this->name;
        } else {
            $this->locationName = '@' . $this->name;
        }

        return $this;
    }


}
