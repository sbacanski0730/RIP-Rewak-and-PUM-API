<?php

namespace App\Entity;

use App\Repository\WorkerRepository;

use App\Entity\Department;

use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiFilter;

#[ApiResource(operations: [
    new GetCollection(),
    new Get()
])]
#[ORM\Entity(repositoryClass: WorkerRepository::class)]
class Worker
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[ORM\ManyToMany(targetEntity: Department::class, inversedBy: 'workers')]
    private $departments;

    #[ApiProperty]
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'worker', fetch: "EAGER")]
    private $events;

    public function __construct() {
        $this->departments = new ArrayCollection();
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

    public function getDepartments(): Collection
    {
        return $this->departments;
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

    public function setDepartments($departments): self
    {
        $this->departments = $departments;
        return $this;
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }
    public function addEvent($event)
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }
        return $this;
    }

}
