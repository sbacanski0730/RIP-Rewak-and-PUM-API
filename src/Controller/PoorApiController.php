<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use App\Repository\WorkerRepository;
use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Department;
use App\Entity\Worker;
use App\Entity\Building;
use App\Entity\Room;
use App\Entity\Course;

class PoorApiController extends AbstractController
{

    private $departmentRepository;
    private $workerRepository;
    private $buildingRepository;
    private $roomRepository;

    public function __construct(DepartmentRepository $departmentRepository, WorkerRepository $workerRepository, BuildingRepository $buildingRepository, RoomRepository $roomRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->workerRepository = $workerRepository;
        $this->buildingRepository = $buildingRepository;
        $this->roomRepository = $roomRepository;
        \ini_set('memory_limit', '840M');
    }

    #[Route('/workers-group', name: 'workersGroup')]
    function workersGroup()
    {
        $array = [];
        $departaments = $this->departmentRepository->findAll();

        foreach ($departaments as $departament) {

            $departmentsMap[] =(object) ['name' => $departament->getName()];
        }
            return new JsonResponse($departmentsMap);
        
    }

    #[Route('/workers-group/{name}', name: 'workersGroupByDepartment')]
    function workersGroupByDepartment(string $name)
    {
        $department = $this->departmentRepository->findOneByName($name);

        $workers = [];
        foreach ($department->getWorkers() as $worker){
            $workers[] =(object) ['name' => $worker->getName()];
        }

        return new JsonResponse( $workers);
    }

    #[Route('/workers-group/{departmentName}/{workerName}', name: 'workersEventByGroupByDepartment')]
    function workersEventByGroupByDepartment(string $departmentName, string $workerName)
    {
        $eventsMap = [];
        $worker = $this->workerRepository->findByName($workerName);
        foreach($worker->getEvents() as $event){
            $eventDepartmentName = $event->getGroup()->getCourse()->getDepartment()->getName();
            if($eventDepartmentName == $departmentName){
                $eventsMap[] =(object) [
                    "timeStart" => $event->getStartTime(),
                    "timeEnd" => $event->getEndTime(),
                    "profesor" => $event->getWorker()->getName(),
                    "room" => $event->getRoom()->getName(),
                    "subject" => $event->getSubject(),
                    "group" => $event->getGroup()->getId()
                ];
            }
        }

       
        return new JsonResponse( $eventsMap);
    }


    #[Route('/rooms', name: 'rooms')]
    function rooms()
    {
        $buildingsMap = [];
        $buildings = $this->buildingRepository->findAll();
        foreach ($buildings as $build) {
            if($build->getId() != 'online'){
            $buildingsMap[] =(object) ['name' => $build->getId()];
            }
        }

        return new JsonResponse($buildingsMap);
    }

    #[Route('/rooms/{id}', name: 'roomByBuildingID')]
    function roomByBuildingID(string $id)
    {
        $roomMap = [];
       // $building = $this->buildingRepository->findOneBy(['name' => $name]);
        $building = $this->buildingRepository->find($id);
        //$array[] = $name;
        foreach ($building->getRooms() as $room) {

            $roomMap[] =(object) ['name' => $room->getName()];
        }
        return new JsonResponse($roomMap);
    }

    #[Route('/rooms/{buildingId}/{roomNumber}', name: 'eventsByRoomNumber')]
    function eventsByRoomNumber(string $buildingId, string $roomNumber)
    {
        $eventMap = [];
       // $building = $this->buildingRepository->findOneBy(['name' => $name]);
        $rooms = $this->buildingRepository->find($buildingId)->getRooms();
        foreach ($rooms as $room) {
            if ($room->getName() == $roomNumber) {
                
                foreach ($room->getEvents() as $event) {

                    $eventMap[] =(object) [
                    "timeStart" => $event->getStartTime(),
                    "timeEnd" => $event->getEndTime(),
                    "profesor" => $event->getWorker()->getName(),
                    "subject" => $event->getSubject()
                ];
                }
                break;
            }
        }
        return new JsonResponse($eventMap);
    }


    #[Route('/courses/{name}', name: 'coursesByDepartment')]
    function coursesByDepartment(string $name)
    {
        $courseMap = [];
        $department = $this->departmentRepository->findOneByName($name);
        
        foreach ($department->getCourses() as $course) {

            $courseMap[] = (object) ['name' => $course->getName()];
        }
        
        return new JsonResponse($courseMap);
    }

    #[Route('/courses/{nameDepartment}/{nameCourse}', name: 'eventByCourseInDepartment')]
    function eventByCourseInDepartment(string $nameDepartment, string $nameCourse)
    {
        $eventMap = [];
        $department = $this->departmentRepository->findOneByName($nameDepartment);
        
        foreach ($department->getCourses() as $course) {
            if ($course->getName() == $nameCourse) {
                foreach($course->getGroups() as $group){
                    foreach ($group->getEvents() as $event) {
                        $eventMap[] = (object) [
                            "timeStart" => $event->getStartTime(),
                            "timeEnd" => $event->getEndTime(),
                            "room" => $event->getRoom()->getName(),
                            "profesor" => $event->getWorker()->getName(),
                            "subject" => $event->getSubject(),
                            "group" => $event->getGroup()->getId()
                        ];
                    }
                }
                break;
            }
        }
        
        return new JsonResponse($eventMap);
    }

}