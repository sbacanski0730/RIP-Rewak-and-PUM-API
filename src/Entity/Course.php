<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;

use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    denormalizationContext: ['groups' => ['write']],
    operations: [
    new GetCollection(),
    new Get(),
])]
#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = "";

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    
    #[Groups('write')]
    #[ORM\OneToMany(targetEntity: Group::class, mappedBy: 'course')]
    private $groups;

    #[Groups('write')]
    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'courses')]
    private $department;

    public function __construct() {
        $this->groups = new ArrayCollection();
    }


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function getGroups()
    {
        return $this->groups;
    }
    public function setDepartment($department): self
    {
        $this->department = $department;

        return $this;
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
    public function setGroups($groups): self
    {
        $this->groups = $groups;
        return $this;
    }
}
