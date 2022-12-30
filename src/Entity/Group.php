<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\Column]

    private ?string $id = "";

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'group')]
    private $events;


    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'groups')]
    private $course;

    public function __construct() {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setCourse($course): self
    {
        $this->course = $course;

        return $this;
    }
}
