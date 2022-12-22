<?php

namespace App\Entity;

use App\Repository\WorkerRepository;

use App\Entity\Department;

use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;

#[ORM\Entity(repositoryClass: WorkerRepository::class)]
class Worker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[ORM\ManyToMany(targetEntity: Department::class)]
    private $departments;

    #[ApiProperty]
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'worker')]
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

    public function getEvents(): Collection
    {
        return $this->events;
    }

}
