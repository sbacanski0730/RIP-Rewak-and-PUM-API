<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DepartmentRepository;
use App\Entity\Worker;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
#[ApiResource(operations: [
    new GetCollection()
])]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Worker::class)]
    private $workers;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'department')]
    private $events;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct() {
        $this->workers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
