<?php


namespace App\Entity;

use DateTime;
use App\Repository\EventRepository;
use App\Entity\Room;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\GetEventsForCourse;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiFilter;

#[ApiResource(operations: [
    new GetCollection(),
    new Get(),
    new GetCollection(
        name: '_api_/courses/{id}/events',
        uriTemplate: '/courses/{id}/events',
        // description: 'Retrieves the collection of Event resources related to a Course',
        controller: GetEventsForCourse::class
    )
])]
#[ApiFilter(SearchFilter::class, properties: ['worker.id' => 'exact'])]
#[ApiFilter(SearchFilter::class, properties: ['room.id' => 'exact'])]
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
    
    public function getWorker(): Worker
    {
        return $this->worker;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setStartTime($startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }
    public function setEndTime($endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function setSubject($subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function setRoom($room): self
    {
        $this->room = $room;
        return $this;
    }
    
    public function setWorker($worker): self
    {
        $this->worker = $worker;
        return $this;
    }
    public function setGroup($group): self
    {
        $this->group = $group;
        return $this;
    }
}
