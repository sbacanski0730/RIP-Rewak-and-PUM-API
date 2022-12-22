<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EventRepository;
use App\Entity\Room;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $startTime;

    #[ORM\Column(type: 'datetime')]
    private DateTime $endTime;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'events')]
    private $room;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'events')]
    private $department;

    #[ORM\ManyToOne(targetEntity: Worker::class, inversedBy: 'events')]
    private $worker;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'events')]
    private $group;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }
    
    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function getWorker(): Worker
    {
        return $this->worker;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }
}
