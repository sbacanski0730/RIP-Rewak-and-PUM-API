<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Course;
use App\Entity\Event;

#[AsController]
class GetEventsForCourse extends AbstractController
{
    #[Route('/api/courses/{id}/events', name: 'events', methods: ['GET'],
        defaults: [
            '_api_resource_class' => Event::class,
            '_api_operation_name' => '_api_/courses/{id}/events',
        ]
    )]
    public function __invoke($id): Array
    {
        return [$id];
    }
}
