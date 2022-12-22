<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use App\Entity\Building;
use App\Entity\Event;
use Doctrine\ORM\Mapping as ORM;



use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Building::class, inversedBy: 'rooms')]
    private $building;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'room')]
    private $events;

    public function __construct() {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function getEvents(): Colection
    {
        return $this->events;
    }
}
