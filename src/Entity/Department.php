<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DepartmentRepository;
use App\Entity\Worker;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
#[ApiResource(operations: [
    new GetCollection(),
    new Get()
])]
class Department
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Worker::class, fetch: "EAGER")]
    private $workers;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'department')]
    private $events;

    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'department', fetch: "EAGER")]
    private $courses;


    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct() {
        $this->workers = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getWorkers(): Collection
    {
        return $this->workers;
    }
    public function getCourses(): Collection
    {
        return $this->courses;
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

    public function setWorkers($workers): self
    {
        $this->workers = $workers;
        return $this;
    }

    public function addCourse($course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
        }
        return $this;
    }
    public function addWorker($worker): self
    {
        if (!$this->workers->contains($worker)) {
            $this->workers[] = $worker;
        }
        return $this;
    }

}
